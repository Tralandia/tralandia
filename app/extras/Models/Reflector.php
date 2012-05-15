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
	
	const ANN_PRIMARY = 'UI\Primary';
	const ANN_SERVICE = 'EA\Service';
	const ANN_SERVICE_LIST = 'EA\ServiceList';

	const ONE_TO_ONE = 'ORM\OneToOne';
	const MANY_TO_ONE = 'ORM\ManyToOne';
	const ONE_TO_MANY = 'ORM\OneToMany';
	const MANY_TO_MANY = 'ORM\ManyToMany';
	const COLUMN = 'ORM\Column';

	
	protected $settings;
	protected $formMask;
	protected $reflectedEntities = array();
	protected $fields = array();
	protected $jsonStructures;


	public function __construct($settings) {
		$this->settings = $settings;

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
		$assocations = $this->getAssocations();
		$values = $form->getValues();

		foreach ($assocations as $entity => $columns) {
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

		foreach ($classReflection->getProperties() as $property) {
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

	public function getFormMask() {
		if(!$this->formMask) {
			$this->formMask = $this->_getFormMask();
		}
		return $this->formMask;
	}

	public function getInlineOptionHtml($type, $value, $controlType) {
		if(!$value) return NULL;
		$a = Html::el('a')->addClass('btn');
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



	private function _getFormMask() {
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

		foreach ($this->getFields($this->settings->serviceClass, $fieldsSettings) as $property) {
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
				'class' => array('default'=> NULL), 
				'addClass' => array('default'=> NULL), 
				'columnClass' => array('default'=> 'span2'), 
				'callback' => array('default'=> NULL), 
				'inlineEditing' => array('default' => NULL), 
				'inlineDeleting' => array('default' => NULL), 
				'inlineCreating' => array('default' => NULL), 
				'startNewRow' => array('default'=> NULL),
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
				if(in_array($type, array('bricksList'))) {
					$fieldMask['ui']['class'] = 'span12';
				} else {
					$fieldMask['ui']['class'] = 'span4';
				}
			}
			
			if($fieldMask['ui']['columnClass']) {
				$fieldMask['ui']['controlOptions']['columnClass'] = $fieldMask['ui']['columnClass'];
			}
			
			if($fieldMask['ui']['startNewRow'] || in_array($type, array('checkboxList', 'bricksList', 'tinymce', 'table', 'json'))) {
				$fieldMask['ui']['controlOptions']['renderBefore'] = Html::el('hr')->addClass('soften');
			}

			if($type == 'checkboxList') {
				$fieldMask['ui']['controlOptions']['renderAfter'] = Html::el('hr')->addClass('soften');
			}

			if($type == 'text') {
				$fieldMask['ui']['control']['type'] = 'AdvancedTextInput';
			} else if($type == 'select') {
				$fieldMask['ui']['control']['type'] = 'AdvancedSelectBox';
			} else if($type == 'checkboxList') {
				$fieldMask['ui']['control']['type'] = 'AdvancedCheckboxList';
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

			if($associationType = $this->getAssocationType($property)) {
				$association = $property->getAnnotation($associationType);
				$targetEntity = $association->targetEntity;
				$fieldMask['targetEntity'] = array();
				if (!Strings::startsWith($targetEntity, 'Entity')) {
					$entity = new \Nette\Reflection\ClassType('\Entity\Dictionary\Language'); //@todo - opravit
					$targetEntity = $entity->getNamespaceName() . '\\' . $targetEntity;
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
					$fieldMask['ui']['callback'] = 'getAllAsPairs';
				}

				if(is_string($fieldMask['ui']['callback'])) {
					$fieldMask['ui']['callback'] = array('method' => $fieldMask['ui']['callback']);
				}

				$fieldMask['ui']['callback']['method'] = $this->getCallback($fieldMask['targetEntity']['serviceListName'], $fieldMask['ui']['callback']['method']);

				if(!array_key_exists('arguments', $fieldMask['ui']['callback'])) {
					$fieldMask['ui']['callback']['arguments'] = array($fieldMask['targetEntity']['primaryKey'], $fieldMask['targetEntity']['primaryValue']);
				}
			}

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
			//debug($property);
			$ui = $property->ui;
			$control = $container->{'add' . $ui->control->type}(
				$propertyName,
				Html::el('b')->add($ui->label->name)
			);

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

			if(isset($ui->control->class)) {
				$control->getControlPrototype()->class($ui->control->class);
			}
			if(isset($ui->control->addClass)) {
				$control->getControlPrototype()->addClass($ui->control->addClass);
			}

			if(isset($ui->control->disabled)) $control->setDisabled($ui->control->disabled);
			
			if ($control instanceof \Extras\Forms\Controls\AdvancedSelectBox 
				|| $control instanceof \Extras\Forms\Controls\AdvancedCheckboxList
				|| $control instanceof \Nette\Forms\Controls\MultiSelectBox) 
			{
				$targetEntity = $property->targetEntity;
				if (isset($ui->callback)) {
					// data volane cez callback
					$control->setItems(call_user_func_array($ui->callback->method, (array) $ui->callback->arguments));
				} elseif (isset($ui->options)) {
					// data volane cez options
					$control->setItems($options);
				} else {
					throw new \Exception("Callback alebo options v `{$classReflection->getConstant('MAIN_ENTITY_NAME')} - {$propertyName}` nie sú validné");
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

		}

		foreach ($mask->buttons as $buttonName => $button) {
			$control = $container->{'add' . $button->type}(
				$buttonName,
				$button->label
			);

			$control->setOption('renderBefore', Html::el('hr')->addClass('soften'));

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
	
	/**
	 * Vrati asociacne vztahy
	 */
	public function getAssocations() {
		$return = array();
		foreach ($this->reflectedEntities as $class) {
			$classReflection = ClassType::from($class);
			foreach ($classReflection->getProperties() as $property) {

				$associationType = $this->getAssocationType($property);
				if(!$associationType) continue;
				
				$association = $property->getAnnotation($associationType);
				if(array_key_exists('inversedBy', $association)) {
					continue;
				}

				$return[$class][$property->getName()] = $association->targetEntity;
			}
		}
		return $return;
	}

	public function getAssocationType($property) {
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
		return ClassType::from($class)->getAnnotation(self::ANN_PRIMARY);
	}

	public static function getEntityServiceName($class) {
		$ann = ClassType::from($class)->getAnnotation(self::ANN_SERVICE);
		return $ann->name;
	}

	public static function getEntityServiceListName($class) {
		$ann = ClassType::from($class)->getAnnotation(self::ANN_SERVICE_LIST);
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