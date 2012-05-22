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

	public function getFormMask($service) {
		if(!$this->formMask) {
			$this->formMask = $this->_getFormMask($service);
		}
		return $this->formMask;
	}

	public function getInlineOptionHtml($type, $value, $controlType) {
		if(!$value) return NULL;
		$a = Html::el('a')
			->addClass('btn btn-hidden pull-left')
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



	private function _getFormMask($service) {
		$mask = array();
		$mask['classReflection'] = $this->getServiceReflection($this->settings->serviceClass);
		$mask['entityReflection'] = $this->getSerivcesEntityReflection($mask['classReflection']);

		$formSettings = $this->settings->params->form;

		$mask['form'] = array();
		$mask['form']['containerName'] = $this->getContainerName();


		$fieldOptions = array( 
			'class' => array('default'=> NULL), 
			'addClass' => array('default'=> NULL),
		);

		foreach ($fieldOptions as $key => $value) {
			$mask['form'][$key] = Arrays::get($formSettings, $key, $value['default']);
		}



		$fieldsSettings = $this->settings->params->form->fields;
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
			$options = NULL;

			$fieldOptions = array(
				'control' => array('default'=> NULL), 
				'label' => array('default'=> ucfirst($name)), 
				'description' => array('default'=> NULL), 
				'class' => array('default'=> NULL), 
				'addClass' => array('default'=> NULL), 
				'callback' => array('default'=> NULL), 
				'inlineEditing' => array('default' => NULL), 
				'inlineDeleting' => array('default' => NULL), 
				'inlineCreating' => array('default' => NULL), 
				'startNewRow' => array('default'=> NULL),
				'validation' => array('default'=> NULL),
			);


			$options = Arrays::get($fieldsSettings, array($name, 'options'), $options);

			foreach ($fieldOptions as $key => $value) {
				$fieldMask['ui'][$key] = Arrays::get($fieldsSettings, array($name, $key), $value['default']);
			}


			if(is_string($fieldMask['ui']['label'])) {
				$fieldMask['ui']['label'] = array('name' => $fieldMask['ui']['label']);
			}

			if(is_string($fieldMask['ui']['control'])) {
				$fieldMask['ui']['control'] = array('type' => $fieldMask['ui']['control']);
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

			if($type == 'phrase') {
				$type = $fieldMask['ui']['control']['type'] = 'text';
				if(!isset($fieldMask['ui']['control']['disabled'])) $fieldMask['ui']['control']['disabled'] = true;
			} else if($type == 'tinymce') {
				$fieldMask['ui']['control']['type'] = 'textArea';
				$fieldMask['ui']['control']['addClass'] = (isset($fieldMask['ui']['control']['addClass']) ? $fieldMask['ui']['control']['addClass'].' ' : NULL) . 'tinymce';
			} else if($type == 'multiSelect') {
				$fieldMask['ui']['control']['addClass'] = (isset($fieldMask['ui']['control']['addClass']) ? $fieldMask['ui']['control']['addClass'].' ' : NULL) . 'multiselect';

			}

			$fieldMask['ui']['name'] = $name;
			$fieldMask['ui']['control']['type'] = ucfirst($fieldMask['ui']['control']['type']);
			$fieldMask['ui']['nameSingular'] = Strings::toSingular(ucfirst($fieldMask['ui']['name']));
			$fieldMask['ui']['options'] = $options;

			if($fieldMask['ui']['class'] === NULL && !in_array($type, array('checkboxList', 'tinymce'))) {
				if(in_array($type, array('json'))) {
					$fieldMask['ui']['class'] = 'span12 json-list';
				} else if(in_array($type, array('bricksList'))) {
					$fieldMask['ui']['class'] = 'span12';
				} else {
					$fieldMask['ui']['class'] = 'span3';
				}
			}
			
			if(isset($fieldMask['ui']['control']['columnClass'])) {
				$fieldMask['ui']['controlOptions']['columnClass'] = $fieldMask['ui']['control']['columnClass'];
				unset($fieldMask['ui']['control']['columnClass']);
			}

			if(isset($fieldMask['ui']['control']['label'])) {
				$fieldMask['ui']['controlOptions']['label'] = $fieldMask['ui']['control']['label'];
			}
			
			if($fieldMask['ui']['startNewRow'] || (in_array($type, array('checkboxList', 'bricksList', 'tinymce', 'table', 'json')) && $fieldMask['ui']['startNewRow'] !== false)) {
				$fieldMask['ui']['controlOptions']['renderBefore'] = Html::el('hr')->addClass('soften');
			}

			if($type == 'checkboxList') {
				$fieldMask['ui']['controlOptions']['renderAfter'] = Html::el('hr')->addClass('soften');
			}

			if($type == 'text') {
				$fieldMask['ui']['control']['type'] = 'AdvancedTextInput';
			} else if($type == 'checkbox') {
				$fieldMask['ui']['control']['type'] = 'AdvancedCheckBox';
			} else if($type == 'select') {
				$fieldMask['ui']['control']['type'] = 'AdvancedSelectBox';
			} else if($type == 'checkboxList') {
				$fieldMask['ui']['control']['type'] = 'AdvancedCheckBoxList';
			} else if($type == 'table') {
				$fieldMask['ui']['control']['type'] = 'AdvancedTable';
			} else if($type == 'json') {
				$fieldMask['ui']['control']['type'] = 'AdvancedJson';
			} else if($type == 'bricksList') {
				$fieldMask['ui']['control']['type'] = 'AdvancedBricksList';
			} else if($type == 'tinymce') {
				if(!isset($fieldMask['ui']['control']['class'])) {
					$fieldMask['ui']['control']['class'] = 'span12';
					$fieldMask['ui']['class'] = 'span12';
				}
			}

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

			}

			if(in_array($type, array('select', 'checkboxList', 'multiSelect'))) {

				if(!$fieldMask['ui']['callback'] && !$fieldMask['ui']['options']) {
					$fieldMask['ui']['callback'] = 'getPairs';
				}

				if(is_string($fieldMask['ui']['callback'])) {
					$fieldMask['ui']['callback'] = array('method' => $fieldMask['ui']['callback']);
				}

				$fieldMask['ui']['callback']['method'] = $this->getCallback($fieldMask['targetEntity']['serviceListName'], $fieldMask['ui']['callback']['method']);

				if(!array_key_exists('arguments', $fieldMask['ui']['callback'])) {
					$fieldMask['ui']['callback']['arguments'] = array($fieldMask['targetEntity']['primaryKey'], $fieldMask['targetEntity']['primaryValue']);
				}
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

		foreach ($mask->fields as $propertyName => $property) {
			unset($ui, $control, $validators, $association);
			// debug($property);
			$ui = $property->ui;
			$control = $container->{'add' . $ui->control->type}(
				$propertyName,
				Html::el('b')->add($ui->label->name.':')
			);


			if(isset($ui->validation)) {
				foreach ($ui->validation as $key => $value) {
					$value = iterator_to_array($value);
					$method = array_shift($value);
					if(in_array($value[0], array('PATTERN', 'EQUAL', 'IS_IN', 'VALID', 'MAX_FILE_SIZE', 'MIME_TYPE', 'IMAGE'))) {
						$value[0] = constant('\Nette\Application\UI\Form::'.$value[0]);
					}
					$t = call_user_func_array(array($control, $method), $value);
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
				$control->getLabelPrototype()->addAttributes(array(
					'data-title' => $ui->description->title,
					'data-content' => $ui->description->content,
					'rel' => 'popover',
				));
			}

			if(isset($ui->control->class)) {
				$control->getControlPrototype()->class($ui->control->class);
			}
			if(isset($ui->control->addClass)) {
				$control->getControlPrototype()->addClass($ui->control->addClass);
			}

			if(isset($ui->control->disabled)) $control->setDisabled($ui->control->disabled);
			
			if ($control instanceof \Extras\Forms\Controls\AdvancedSelectBox 
				|| $control instanceof \Extras\Forms\Controls\AdvancedCheckBoxList
				|| $control instanceof \Nette\Forms\Controls\MultiSelectBox) 
			{
				$targetEntity = $property->targetEntity;
				if (isset($ui->callback)) {
					// data volane cez callback
					$items = call_user_func_array($ui->callback->method, (array) $ui->callback->arguments);
				} elseif (isset($ui->options)) {
					// data volane cez options
					$items = $ui->options;
				} else {
					throw new \Exception("Callback alebo options v `{$classReflection->getConstant('MAIN_ENTITY_NAME')} - {$propertyName}` nie sú validné");
				}
				if($control instanceof \Extras\Forms\Controls\AdvancedSelectBox) {
					foreach ($items as $key => $value) {
						$item = Html::el('option')->setText($value)->setValue($key);
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
				$control->setItems($items);

			}

			if($control instanceof \Extras\Forms\Controls\AdvancedJson) {
				$structure = $this->getJsonStructure($mask->entityReflection);
				$control->setStructure((array) $structure[$ui->name]);
			}

			if($control instanceof \Extras\Forms\Controls\AdvancedTable) {
				$control->setColumns($ui->control->columns);
				$control->setRows($ui->control->rows);
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
	}

	public function getJsonStructure($entityReflection) {
		list(, $name) = explode('\\', $entityReflection->getName(), 2);
		if(!$this->jsonStructures) {
			$config = new \Nette\Config\Loader;
			
			$this->jsonStructures = $config->load(APP_DIR . '/configs/entities/jsonStructures.neon');
		}
		return $this->jsonStructures[$name];
	}
	
	public function getPhraseAssociations() {
		$a = $this->getAssociations(self::ONE_TO_ONE);
		
		foreach ($a as $key => $value) {

			debug($value);
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
	
	private function getCallback($class, $callback) {
		return callback($class, trim($callback));
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