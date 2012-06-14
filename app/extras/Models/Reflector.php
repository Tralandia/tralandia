<?php

namespace Extras\Models;

use Nette,
	Nette\ArrayHash,
	Nette\Reflection\ClassType,
	Nette\Reflection\Property,
	Tra\Utils\Strings,
	Tra\Utils\Arrays,
	Nette\Utils\Html,
	Nette\ComponentModel\IContainer;

class Reflector extends Nette\Object {
	
	const ANN_PRIMARY = 'EA\Primary';
	const ANN_SERVICE = 'EA\Service';
	const ANN_SERVICE_LIST = 'EA\ServiceList';
	const ANN_COLUMN = 'ORM\Column';

	const ONE_TO_ONE = 'ORM\OneToOne';
	const MANY_TO_ONE = 'ORM\ManyToOne';
	const ONE_TO_MANY = 'ORM\OneToMany';
	const MANY_TO_MANY = 'ORM\ManyToMany';
	const COLUMN = 'ORM\Column';

	
	
	protected $settings;
	protected $presenter;
	protected $formMask;
	protected $reflectedEntities = array();
	protected $fields = array();
	protected $jsonStructures;


	public function __construct($settings, $presenter) {
		$this->settings = $settings;
		$this->presenter = $presenter;

		//debug($this->service);
	}

	/**
	 * Vrati nazov hlavnej entity
	 * @return string
	 */
	public function getMainEntityName() {
		$classReflection = $this->getServiceReflection($this->settings->serviceClass);
		return $classReflection->getConstant('MAIN_ENTITY_NAME');
	}

	/**
	 * Vrati kratky nazov hlavnej entity
	 * @return string
	 */
	public function getMainEntityShortName() {
		$classReflection = $this->getServiceReflection($this->settings->serviceClass);
		$classReflection = ClassType::from($classReflection->getConstant('MAIN_ENTITY_NAME'));
		return $classReflection->getShortName();
	}

	/**
	 * Vrati prisluxny nazov kontaineru pre reflektor
	 * @return string
	 */
	public function getContainerName() {
		$classReflection = $this->getServiceReflection($this->settings->serviceClass);
		return str_replace('\\', '', $classReflection->getName());
	}

	/**
	 * Vrati prisluxny kontainer pre reflektor
	 * @return string
	 */
	public function getContainer(IContainer $form) {
		return $form->getComponent($this->getContainerName());
	}

	/**
	 * Vrati data pre servisu
	 * @return Nete\ArrayHash
	 */
	public function getPrepareValues(IContainer $form) {
		return $form->getComponent($this->getContainerName())->getValues();
	}

	/**
	 * Pripravi data
	 */
	public function prepareData(IContainer $form) {
		$associations = $this->getAssociations();
		$values = $form->getValues();

		foreach ($associations as $entity => $columns) {
			$container = $form->getComponent($entity);
			foreach ($columns as $name => $target) {
				$control = $container->getComponent($name);
				$values->{$entity}->{$name} = $this->em->find($target, $control->getValue());
			}
		}
		return $values;
	}
	

	protected function allow($class, $fields = array()) {		
		$classReflection = $this->getServiceReflection($class);
		$classReflection = ClassType::from($classReflection->getConstant('MAIN_ENTITY_NAME'));

		foreach ($fields as $key => $value) {
			$property = $classReflection->getProperty($key);
			if(count($fields) === 0 || array_key_exists($property->name, $fields))
				$this->fields[$class][$property->name] = $property;
		}
		
		return $this;
	}
	
	/**
	 * 
	 */
	protected function except($class, array $fields) {
		foreach ($this->getFields($class) as $field) {
			if(in_array($field->name, $fields)) unset($this->fields[$class][$field->name]);
		}
		
		return $this;
	}

	/**
	 * Vrati zoznam vlastnoti o ktore sa bude rozsirovat
	 * @param string
	 */
	protected function getFields($class, $allow = NULL, $except = NULL) {
		if(!isset($this->fields[$class]) || $allow) {
			$this->fields = array();
			$this->allow($class, $allow);
		}
		return $this->fields[$class];
	}

	public function getFormMask($service, $formSettings) {
		if(!$this->formMask) {
			$this->formMask = $this->_getFormMask($service, $formSettings);
		}
		return $this->formMask;
	}


	public function getInlineOptionHtml($type, $value, $controlType) {
		if(!$value) return NULL;
		$a = Html::el('a')
			->addClass('btn')
			// ->addClass('btn-hidden')
			->setHref(call_user_func_array(array($this->presenter, 'lazyLink'),(array) $value));
		$i = Html::el('i')->addClass('icon-white');

		if($type == 'inlineCreating') {
			$a->addClass('btn-success create');
			$i->addClass('icon-plus');
		} else if($type == 'inlineEditing') {
			$a->addClass('btn-info edit');
			$i->addClass('icon-edit');
		} else if($type == 'inlineDeleting') {
			$a->addClass('btn-danger delete');
			$i->addClass('icon-remove');
		}

		if(in_array($controlType, array('checkboxList', 'bricksList'))) {
			$a->addClass('btn-mini');
		} else {
			$a->addClass('btn-small');
		}

		return $a->add($i);		
	}



	private function _getFormMask($service, $formSettings) {
		$mask = array();
		$mask['classReflection'] = $this->getServiceReflection($this->settings->serviceClass);
		$mask['entityReflection'] = $this->getSerivcesEntityReflection($mask['classReflection']);

		$mask['form'] = array();
		$mask['form']['containerName'] = $this->getContainerName();


		$fieldOptions = array( 
			'class' => array('default'=> NULL), 
			'addClass' => array('default'=> NULL),
		);

		foreach ($fieldOptions as $key => $value) {
			$mask['form'][$key] = Arrays::get($formSettings, $key, $value['default']);
		}

		$fieldsSettings = $formSettings->fields;
		$user = $this->presenter->user;
		foreach ($this->getFields($this->settings->serviceClass, $fieldsSettings) as $property) {

			if(!$user->isAllowed($service->getMainEntity(), $property->name . '_show')) {
				continue;
			}

			$fieldMask = array(
				'ui' => NULL,
			);

			$fieldMask['ui'] = array();
			$name = $property->getName();
			$ui = NULL;

			$fieldOptions = array(
				'control' => array('default'=> NULL), 
				'label' => array('default'=> ucfirst($name)), 
				'description' => array('default'=> NULL), 
				'class' => array('default'=> NULL), 
				'addClass' => array('default'=> NULL), 
				'inlineEditing' => array('default' => NULL), 
				'inlineDeleting' => array('default' => NULL), 
				'inlineCreating' => array('default' => NULL), 
				'startNewRow' => array('default'=> NULL),
				'validation' => array('default'=> NULL),
			);



			foreach ($fieldOptions as $key => $value) {
				$fieldMask['ui'][$key] = Arrays::get($fieldsSettings, array($name, $key), $value['default']);
			}


			if(is_string($fieldMask['ui']['label'])) {
				$fieldMask['ui']['label'] = array('name' => $fieldMask['ui']['label']);
			}

			if(is_string($fieldMask['ui']['control']) || !isset($fieldMask['ui']['control']['type'])) {
				throw new \Exception("Control '{$property->name}' neobsahuje atribut 'type'", 1);
			} else if($fieldMask['ui']['control'] instanceof ArrayHash) {
				$fieldMask['ui']['control'] = iterator_to_array($fieldMask['ui']['control']);
			}

			if(is_string($fieldMask['ui']['description'])) {
				$fieldMask['ui']['description'] = array(
					'title' => $fieldMask['ui']['description'],
					'content' => $fieldMask['ui']['description']
				);
			}

			$type = $fieldMask['ui']['control']['type'];

			$fieldMask['ui']['controlOptions']['inlineCreating'] = $this->getInlineOptionHtml('inlineCreating', $fieldMask['ui']['inlineCreating'], $type);
			$fieldMask['ui']['controlOptions']['inlineEditing'] = $this->getInlineOptionHtml('inlineEditing', $fieldMask['ui']['inlineEditing'], $type);
			$fieldMask['ui']['controlOptions']['inlineDeleting'] = $this->getInlineOptionHtml('inlineDeleting', $fieldMask['ui']['inlineDeleting'], $type);

			if($type == 'tinymce') {
				$fieldMask['ui']['control']['type'] = 'textArea';
				$fieldMask['ui']['control']['addClass'] = (isset($fieldMask['ui']['control']['addClass']) ? $fieldMask['ui']['control']['addClass'].' ' : NULL) . 'tinymce';
			} else if($type == 'multiSelect') {
				$fieldMask['ui']['control']['addClass'] = (isset($fieldMask['ui']['control']['addClass']) ? $fieldMask['ui']['control']['addClass'].' ' : NULL) . 'multiselect';

			}

			$fieldMask['ui']['name'] = $name;
			$fieldMask['ui']['control']['type'] = ucfirst($fieldMask['ui']['control']['type']);
			$fieldMask['ui']['nameSingular'] = Strings::toSingular(ucfirst($fieldMask['ui']['name']));
			
			if (isset($fieldMask['ui']['control']['options'])) {
				// if(!isset($fieldMask['ui']['control']['options']['class'])) {
				// 	throw new \Exception("V {$property->name} - options si zabudol nastavit parameter 'class'");
				// }
				if(isset($fieldMask['ui']['control']['options']['pattern'])) {
					$constList = c(new \Nette\Reflection\ClassType($fieldMask['ui']['control']['options']['class']))->getConstants();
					$optionsTemp = array();
					foreach ($constList as $key => $value) {
						if(!Strings::match($key, '~'.$fieldMask['ui']['control']['options']['pattern'].'~')) continue;
						$optionsTemp[$value] = ucfirst($value);
					}
					$fieldMask['ui']['control']['options'] = $optionsTemp;
				} else if($fieldMask['ui']['control']['options'] instanceof \Traversable) {
					$fieldMask['ui']['control']['options'] = iterator_to_array($fieldMask['ui']['control']['options']);
				}
			}

			if($fieldMask['ui']['class'] === NULL) {
				$fieldMask['ui']['class'] = $formSettings->defaultFieldClass;
			}

			if(!array_key_exists('class', $fieldMask['ui']['control']) || $fieldMask['ui']['control']['class'] === NULL) {
				$fieldMask['ui']['control']['class'] = $formSettings->defaultFieldClass;
			}

			
			if(isset($fieldMask['ui']['control']['columnClass'])) {
				$fieldMask['ui']['controlOptions']['columnClass'] = $fieldMask['ui']['control']['columnClass'];
			}

			if(isset($fieldMask['ui']['control']['columns'])) {
				$fieldMask['ui']['controlOptions']['columns'] = $fieldMask['ui']['control']['columns'];
			}

			if(isset($fieldMask['ui']['control']['label'])) {
				$fieldMask['ui']['controlOptions']['label'] = $fieldMask['ui']['control']['label'];
			}
			
			if($type == 'tinymce') {
				$fieldMask['ui']['controlOptions']['showPreview'] = isset($fieldMask['ui']['control']['showPreview']) ? $fieldMask['ui']['control']['showPreview'] : TRUE;
			}
			
			if($fieldMask['ui']['startNewRow']){
				$fieldMask['ui']['controlOptions']['renderBefore'] = Html::el('hr')->addClass('soften');
			}

			$fieldMask['ui']['control']['type'] = 'Advanced' . ucfirst($type);

			if($associationType = $this->getAssociationType($property)) {
				$association = $property->getAnnotation($associationType);
				$targetEntity = $association->targetEntity;
				$fieldMask['targetEntity'] = array();
				if (!Strings::startsWith($targetEntity, 'Entity')) {
					// $entity = new \Nette\Reflection\ClassType('\Entity\Dictionary\Language'); //@todo - opravit
					$targetEntity = $property->getDeclaringClass()->getNamespaceName() . '\\' . $targetEntity;
				}

				$fieldMask['targetEntity']['name'] = $targetEntity;
				$fieldMask['targetEntity']['associationType'] = $associationType;
				$fieldMask['targetEntity']['primaryKey'] = $this->getEntityPrimaryData($targetEntity)->key;
				$fieldMask['targetEntity']['primaryValue'] = $this->getEntityPrimaryData($targetEntity)->value ? : $fieldMask['targetEntity']['primaryKey'];
				$fieldMask['targetEntity']['serviceName'] = $this->getEntityServiceName($targetEntity);
				$fieldMask['targetEntity']['serviceListName'] = $this->getEntityServiceListName($targetEntity);

			} else {
				$fieldMask['column']['type'] = $property->getAnnotation(self::ANN_COLUMN)->type;
			}

			if(in_array($type, array('select', 'checkboxList', 'multiSelect', 'bricksList', 'price', 'address', 'contacts')) && (!isset($fieldMask['ui']['control']['options']) || !is_array($fieldMask['ui']['control']['options']))) {

				if(!array_key_exists('callback', $fieldMask['ui']['control'])) {
					throw new \Exception("Nezadefinoval si callback ani options pre '{$fieldMask['ui']['name']}'");	
				} else if(!array_key_exists('class', $fieldMask['ui']['control']['callback']) || !array_key_exists('method', $fieldMask['ui']['control']['callback']) || !array_key_exists('params', $fieldMask['ui']['control']['callback'])) {
						throw new \Exception("Nezadefinoval si spravne callback pre '{$fieldMask['ui']['name']}'. Skontroluj ci ma tieto atributy: class, method, params");	
				}

				$fieldMask['ui']['control']['callback']['cb'] = callback($fieldMask['ui']['control']['callback']['class'], $fieldMask['ui']['control']['callback']['method']);
			}

			if(array_key_exists('addressLocations', $fieldMask['ui']['control'])) {
				$fieldMask['ui']['control']['addressLocations']['cb'] = callback($fieldMask['ui']['control']['addressLocations']['class'], $fieldMask['ui']['control']['addressLocations']['method']);
			} else if($type == 'contacts') {
				throw new \Exception("Nezadefinoval si addressLocations pre '{$fieldMask['ui']['name']}'");	
			}				

			if($type == 'suggestion') {
				$fieldMask['ui']['controlOptions']['serviceName'] = $fieldMask['targetEntity']['serviceName'];
				$fieldMask['ui']['controlOptions']['serviceList'] = $fieldMask['ui']['control']['suggestion']['serviceList'];
				$fieldMask['ui']['controlOptions']['property'] = $fieldMask['ui']['control']['suggestion']['property'];
			}

			if(!$user->isAllowed($service->getMainEntity(), $property->name . '_edit')) {
				$fieldMask['ui']['control']['disabled'] = true;
			}
			// debug($fieldMask['ui']);
			// @todo vyhadzovat exceptiony ak nieco nieje nastavene OK
			$mask['fields'][$name] = $fieldMask;
		}

		foreach ($this->settings->params->form->buttons as $name => $options) {
			$options = (array) $options;

			$button = array();
			$fieldOptions = array( 
				'type' => array('default'=> 'button'),
				'label' => array('default'=> ucfirst($name)),
				'class' => array('default'=> 'btn btn-large'),
				'addClass' => array('default'=> NULL),
			);

			foreach ($fieldOptions as $key => $value) {
				$button[$key] = Arrays::get($options, $key, $value['default']);
			}

			if($button['type'] == 'backlink') {
				$button['type'] = 'button';
				$backlinkCode = $this->presenter->context->session->getSection('environment')->previousLink;
				$backlink = $this->presenter->link('//this', array('backlink' => $backlinkCode));
				$button['prototype']['onClick'] = 'window.location=\''.$backlink.'\'';
			}
			$button['type'] = ucfirst($button['type']);
			$mask['buttons'][$name] = $button;
		}

		return \Nette\ArrayHash::from($mask);
	}
	
	/**
	 * Rozsiri formular o UI prvky
	 * @param IContainer
	 */
	public function extend(IContainer $form, $mask) {
		$classReflection = $mask->classReflection;
		// \Nette\Diagnostics\Debugger::timer('FORM');

		$formUi = $mask->form;

		if(isset($formUi->class)) {
			$form->getElementPrototype()->class($formUi->class);
		}
		if(isset($formUi->addClass)) {
			$form->getElementPrototype()->addClass($formUi->addClass);
		}

		$container = $formUi->containerName;
		if ($form->getComponent($container, false)) {
			$container = $form->getComponent($container);
		} else {
			$container = $form->addContainer($container);
		}

		if(isset($mask->fields)) {

			foreach ($mask->fields as $propertyName => $property) {
				unset($ui, $control, $validators, $association);
				// \Nette\Diagnostics\Debugger::timer($propertyName);

				$ui = $property->ui;
				$control = $container->{'add' . $ui->control->type}(
					$propertyName,
					Html::el('b')->add($ui->label->name.':')
				);


				if(isset($ui->validation)) {
					$lastCondition = $control;
					foreach ($ui->validation as $key => $value) {
						$value = iterator_to_array($value);
						$method = array_shift($value);
						if(in_array($value[0], array('PATTERN', 'EQUAL', 'IS_IN', 'VALID', 'MAX_FILE_SIZE', 'MIME_TYPE', 'IMAGE', 'URL', 'MIN_LENGTH', 'MAX_LENGTH', 'LENGTH', 'EMAIL', 'INTEGER', 'FLOAT', 'RANGE', 'FILLED'))) {
							$value[0] = constant('\Nette\Application\UI\Form::'.$value[0]);
						}
						if(in_array($method, array('addCondition', 'addConditionOn'))) {
							$lastCondition = call_user_func_array(array($control, $method), $value);
						} else {
							call_user_func_array(array($lastCondition, $method), $value);
						}
					}
				}

				foreach ($ui->controlOptions as $optionKey => $option) {
					$control->setOption($optionKey, $option);
				}
				
				if(isset($ui->class)) {
					$control->setOption('class', $ui->class);
				}
				if(isset($ui->addClass)) {
					$control->setOption('class', $control->getOption('class') . ' ' . $ui->addClass);
				}

				if(isset($ui->label->class)) {
					$control->getLabelPrototype()->addClass($ui->label->class);
				}

				if(isset($ui->description)) {
					$popover = Html::el('span')
						->class('label label-warning pull-right')
						->setText('?')
						->addAttributes(array(
							// 'data-title' => $ui->description->title,
							'title' => $ui->description->content,
							'rel' => 'tooltip',
						));
					$control->getLabelPrototype()->add($popover);
				}

				if(isset($ui->control->class)) {
					$control->getControlPrototype()->class($ui->control->class);
				}
				if(isset($ui->control->addClass)) {
					$control->getControlPrototype()->addClass($ui->control->addClass);
				}

				if(isset($ui->control->disabled)) $control->setDisabled($ui->control->disabled);
				
				if ($control instanceof \Nette\Forms\Controls\SelectBox 
					|| $control instanceof \Extras\Forms\Controls\AdvancedCheckboxList
					|| $control instanceof \Extras\Forms\Controls\AdvancedBricksList) 
				{
					// $targetEntity = $property->targetEntity;
					if (isset($ui->control->callback)) {
						// data volane cez callback
						// debug($ui->control->callback);
						$items = call_user_func_array($ui->control->callback->cb, ($ui->control->callback->params instanceof \Traversable ? iterator_to_array($ui->control->callback->params) : $ui->control->callback->params));


					} elseif (isset($ui->control->options)) {
						// data volane cez options
						$items = $ui->control->options;
					} else {
						throw new \Exception("Callback alebo options v `{$classReflection->getConstant('MAIN_ENTITY_NAME')} - {$propertyName}` nie sú validné");
					}
					if($control instanceof \Nette\Forms\Controls\SelectBox) {
						foreach ($items as $key => $value) {
							if($value instanceof \Entity\Dictionary\Phrase || $value instanceof \Service\Dictionary\Phrase) {
								$value = $this->presenter->translate($value);
							}
							$item = Html::el('option')->setText((string) $value)->setValue($key);
							$attributes = array();
							if($control->getOption('inlineEditing')) {
								$inlineEditingHref = $control->getOption('inlineEditing')->href;
								$attributes['data-editLink'] = $inlineEditingHref->setParameter('id', $key);
							}
							if($control->getOption('inlineDeleting')) {
								$inlineDeletingHref = $control->getOption('inlineDeleting')->href;
								$attributes['data-deleteLink'] = $inlineDeletingHref->setParameter('id', $key);
							}
							if(count($attributes)) $item->addAttributes($attributes);
							$items[$key] = $item;
						}
					}
					$control->setItems($items instanceof \Traversable ? iterator_to_array($items) : $items);

					if(isset($ui->control->prompt)) {
						$control->setPrompt($ui->control->prompt);
					}

				}

				if($control instanceof \Extras\Forms\Controls\AdvancedJson) {
					$structure = $this->getJsonStructure($mask->entityReflection);
					$control->setStructure((array) $structure[$ui->name]);
				}

				if($control instanceof \Extras\Forms\Controls\AdvancedTable) {
					$control->setColumns($ui->control->columns);
					$control->setRows($ui->control->rows);
				}

				if($control instanceof \Extras\Forms\Controls\AdvancedContacts) {
					$control->setOption('addressLocations', $ui->control->addressLocations->cb->invokeArgs(iterator_to_array($ui->control->addressLocations->params)));
				}
				// debug($ui->control->type, $control);
				// debug($propertyName, \Nette\Diagnostics\Debugger::timer($propertyName));
			}
		}

		foreach ($mask->buttons as $buttonName => $button) {
			$control = $container->{'add' . $button->type}(
				$buttonName,
				$button->label
			);

			$control->setOption('renderBefore', Html::el('hr')->addClass('soften'));

			if(isset($button->prototype)) {
				foreach ($button->prototype as $key => $value) {
					$control->getControlPrototype()->{$key}($value);
				}
			}

			if(isset($button->class)) {
				$control->getControlPrototype()->class($button->class);
			}
			if(isset($button->addClass)) {
				$control->getControlPrototype()->addClass($button->addClass);
			}
		}
		// debug('FORM', \Nette\Diagnostics\Debugger::timer('FORM')); 
	}

	public function getJsonStructure($entityReflection) {
		list(, $name) = explode('\\', $entityReflection->getName(), 2);
		if(!$this->jsonStructures) {
			$config = new \Nette\Config\Loader;
			
			$this->jsonStructures = $config->load(APP_DIR . '/configs/entities/jsonStructures.neon');
		}
		if(!isset($this->jsonStructures[$name])) {
			throw new \Exception("Nezadefinoval si json strukturu pre $name.");
			
		}
		return $this->jsonStructures[$name];
	}
	
	public function getPhraseAssociations() {
		$a = $this->getAssociations(self::ONE_TO_ONE);
		
		foreach ($a as $key => $value) {

			// debug($value);
		}
	}
	
	/**
	 * Vrati asociacne vztahy
	 */
	public function getAssociations($filter = NULL) {
		$return = array();
		foreach ($this->reflectedEntities as $class) {
			$classReflection = ClassType::from($class);
			foreach ($classReflection->getProperties() as $property) {

				$associationType = $this->getAssociationType($property);
				// debug($associationType);
				if(!$associationType || ($filter !== NULL && $filter != $associationType) ) continue;
				
				$association = $property->getAnnotation($associationType);
				if(array_key_exists('inversedBy', $association)) {
					continue;
				}

				$return[$property->getName()] = $association->targetEntity;
			}
		}
		return $return;
	}


	public function getAssociationType($property) {
		$associationType = NULL;

		if ($property->hasAnnotation(self::MANY_TO_MANY)) {
			$associationType = self::MANY_TO_MANY;
		} else if ($property->hasAnnotation(self::MANY_TO_ONE)){
			$associationType = self::MANY_TO_ONE;
		} else if ($property->hasAnnotation(self::ONE_TO_MANY)){
			$associationType = self::ONE_TO_MANY;
		} else if ($property->hasAnnotation(self::ONE_TO_ONE)){
			$associationType = self::ONE_TO_ONE;
		}
		
		return $associationType;
	}
	
	/**
	 * Pripravi data
	 */
	public static function getEntityPrimaryData($class) {
		$primaryAnn = ClassType::from($class)->getAnnotation(self::ANN_PRIMARY);
		if(!$primaryAnn) {
			throw new \Exception("V $class nie je zadefinovane @".self::ANN_PRIMARY);
		}
		return $primaryAnn;
	}

	public static function getEntityServiceName($class) {
		$ann = ClassType::from($class)->getAnnotation(self::ANN_SERVICE);
		if(!$ann->name) {
			throw new \Exception("V $class nie je zadefinovane @".self::ANN_SERVICE);
		}
		return $ann->name;
	}

	public static function getEntityServiceListName($class) {
		$ann = ClassType::from($class)->getAnnotation(self::ANN_SERVICE_LIST);
		if(!$ann->name) {
			throw new \Exception("V $class nie je zadefinovane @".self::ANN_SERVICE_LIST);
		}
		return $ann->name;
	}
	
	/**
	 * Pripravi data
	 */
	public static function getAnnotation($class, $property, $annotation = 'UIControl') {
		if (ClassType::from($class)->hasProperty($property)) {
			return ClassType::from($class)->getProperty($property)->getAnnotation($annotation);
		}
		return false;
	}
	
	private function getOptions($classReflection, $options) {
		$return = array();
		foreach (explode(',', $options) as $option) {
			list($option, $value) = explode(':', trim($option));
			$option = trim($option);
			$value = trim($value);
			if ($classReflection->hasConstant($option)) {
				$return[$classReflection->getConstant($option)] = trim($value, "\'");
			}
		}
		
		return $return;
	}
	
	
	private function getServiceReflection($class) {
		$classReflection = ClassType::from($class);
		$entityReflection = $this->getSerivcesEntityReflection($classReflection);
		
		// poznacim si, ktore entity som v tejto service uz reflektoval
		if (!in_array($entityReflection->getName(), $this->reflectedEntities)) {
			array_push($this->reflectedEntities, $entityReflection->getName());
		}

		return $classReflection;
	}

	private function getSerivcesEntityReflection($serviceReflection){
		return ClassType::from($serviceReflection->getConstant('MAIN_ENTITY_NAME'));
	}
	
}