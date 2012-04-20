<?php

namespace Extras\Models;

use Nette,
	Nette\ArrayHash,
	Nette\Reflection\ClassType,
	Nette\Reflection\Property,
	Nette\Utils\Strings,
	Tra\Utils\Arrays,
	Nette\ComponentModel\IContainer;

class Reflector extends Nette\Object {
	
	const ANN_PRIMARY = 'UI\Primary';
	const UI_CONTROL = 'UI\Control';
	const ANN_SERVICE = 'EA\Service';
	const ANN_SERVICE_LIST = 'EA\ServiceList';

	const ONE_TO_ONE = 'ORM\OneToOne';
	const MANY_TO_ONE = 'ORM\ManyToOne';
	const ONE_TO_MANY = 'ORM\OneToMany';
	const MANY_TO_MANY = 'ORM\ManyToMany';
	const COLUMN = 'ORM\Column';

	
	protected $service = null;
	protected $reflectedEntities = array();
	protected $fields = array();


	public function __construct($service) {
		$this->service = $service;


		//debug($this->service);
	}

	/**
	 * Vrati nazov hlavnej entity
	 * @return string
	 */
	public function getMainEntityName() {
		$classReflection = $this->getServiceReflection($this->service);
		return $classReflection->getConstant('MAIN_ENTITY_NAME');
	}

	/**
	 * Vrati kratky nazov hlavnej entity
	 * @return string
	 */
	public function getMainEntityShortName() {
		$classReflection = $this->getServiceReflection($this->service);
		$classReflection = ClassType::from($classReflection->getConstant('MAIN_ENTITY_NAME'));
		return $classReflection->getShortName();
	}

	/**
	 * Vrati prisluxny nazov kontaineru pre reflektor
	 * @return string
	 */
	public function getContainerName() {
		$classReflection = $this->getServiceReflection($this->service);
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
	 * Vrati data pre servisu
	 * @return array
	 */
/*	public function getMask() {
		$mask = array();
		$assocations = $this->getAssocations();
		//$collection = $this->getCollections();

		$classReflection = $this->getServiceReflection($this->service);
		$classReflection = ClassType::from($classReflection->getConstant('MAIN_ENTITY_NAME'));

		foreach ($this->getFields($this->service) as $property) {
			if ($pmask = $this->getPropertyMask($property, $classReflection)) {
				$mask[] = $pmask;
				break;
			}
			
			//debug($options);
		}

		return $mask;
	}
*/
	/**
	 * Vrati masku pre ziskanie dat pre vsupnu property
	 * @return array
	 */
/*	public function getPropertyMask(Property $property, ClassType $entity) {
		if (!$entity->hasMethod('get' .  ucfirst($property->getName()))) {
			return false;
		}
		$options = ArrayHash::from(array(
			'name' => $property->getName(),
			'defaultValue' => null,
			'items' => null,
			'type' => null,
			'sourceEntity' => $entity->getName(),
			'targetEntities' => array()
		));

		$associationType = $this->getAssocationType($property);
		if ($property->hasAnnotation(self::ONE_TO_ONE) || $property->hasAnnotation(self::MANY_TO_ONE)) {
			$toOne = $property->hasAnnotation(self::ONE_TO_ONE)
				? $property->getAnnotation(self::ONE_TO_ONE)
				: $property->getAnnotation(self::MANY_TO_ONE);

			$options->type = $property->hasAnnotation(self::ONE_TO_ONE)
				? self::ONE_TO_ONE
				: self::MANY_TO_ONE;
			
			if (!Strings::startsWith($toOne->targetEntity, 'Entity')) {
				$targetEntity = $entity->getNamespaceName() . '\\' . $toOne->targetEntity;
			} else {
				$targetEntity = $toOne->targetEntity;
			}

			$class = ClassType::from($targetEntity);
			if ($class->hasAnnotation(self::ANN_PRIMARY)) {
				$options->targetEntities = array(
					$toOne->targetEntity => $class->getAnnotation(self::ANN_PRIMARY)
				);
			} else {
				$options->targetEntities = array(
					$toOne->targetEntity => array('key' => 'id', 'value' => 'id'),
				);
			}
		} elseif ($property->hasAnnotation(self::ONE_TO_MANY) || $property->hasAnnotation(self::MANY_TO_MANY)) {
			$options->type = $property->hasAnnotation(self::ONE_TO_MANY)
				? self::ONE_TO_MANY
				: self::MANY_TO_MANY;
			// TODO: dokoncit
		}

		return ArrayHash::from($options);
	}
*/
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

	public function getFormMask($settings = NULL) {
		$mask = array();
		$mask['classReflection'] = $this->getServiceReflection($this->service);
		$mask['containerName'] = $this->getContainerName();

		$settingsFields = NULL;
		if($settings && array_key_exists('fields', $settings) && $settings->fields instanceof \Traversable) {
			$settingsFields = $settings->fields;
		}

		foreach ($this->getFields($this->service, $settingsFields) as $property) {
			$fieldMask = array(
				'ui' => NULL,
			);

			$name = $property->getName();
			$ui = NULL;
			$type = NULL;
			$label = NULL;
			$callback = NULL;
			$options = NULL;

			if ($property->hasAnnotation(self::UI_CONTROL)) {
				$ui = $property->getAnnotation(self::UI_CONTROL);
				$type = $ui->type;
				$label = isset($ui->label) ? $ui->label : ucfirst($name);
				$callback = isset($ui->callback) ? $ui->callback : NULL;
				$callbackArgs = isset($ui->callbackArgs) ? $ui->callbackArgs : NULL;
				$options = isset($ui->options) ? $this->getOptions($classReflection, $ui->options) : NULL;
			}
			if($settingsFields) {
				$type = Arrays::get($settingsFields, array($name, 'type'), $type);
				$label = Arrays::get($settingsFields, array($name, 'label'), $label);
				$callback = Arrays::get($settingsFields, array($name, 'callback'), $callback);
				$callbackArgs = Arrays::get($settingsFields, array($name, 'callbackArgs'), $callbackArgs);
				$options = Arrays::get($settingsFields, array($name, 'options'), $options);
			}

			$fieldMask['ui'] = array(
				'name' => $name,
				'type' => ucfirst($type),
				'label' => $label,
				'callback' => $callback,
				'callbackArgs' => $callbackArgs,
				'options' => $options,
			);

			if($associationType = $this->getAssocationType($property)) {
				$association = $property->getAnnotation($associationType);
				$targetEntity = $association->targetEntity;
				$fieldMask['targetEntity'] = array();
				if (!Strings::startsWith($targetEntity, 'Entity')) {
					$targetEntity = $entity->getNamespaceName() . '\\' . $toOne->targetEntity;
				}

				$fieldMask['targetEntity']['name'] = $targetEntity;
				$fieldMask['targetEntity']['associationType'] = $associationType;
				$fieldMask['targetEntity']['primaryKey'] = $this->getEntityPrimaryData($targetEntity)->key;
				$fieldMask['targetEntity']['primaryValue'] = $this->getEntityPrimaryData($targetEntity)->value ? : $fieldMask['targetEntity']['primaryKey'];
				$fieldMask['targetEntity']['serviceName'] = $this->getEntityServiceName($targetEntity);
				$fieldMask['targetEntity']['serviceListName'] = $this->getEntityServiceListName($targetEntity);

			}

			if($type === 'select') {
				if(!$fieldMask['ui']['callback'] && !$fieldMask['ui']['options']) {
					$fieldMask['ui']['callback'] = 'getAllAsPairs';
				}

				$fieldMask['ui']['callback'] = $this->getCallback($fieldMask['targetEntity']['serviceListName'], $fieldMask['ui']['callback']);
				if(!$fieldMask['ui']['callbackArgs']) {
					$fieldMask['ui']['callbackArgs'] = array($fieldMask['targetEntity']['primaryKey'], $fieldMask['targetEntity']['primaryValue']);
				}
			}

			$mask['fields'][$name] = $fieldMask;
		}
		
		//debug($mask['fields']);
		return \Nette\ArrayHash::from($mask);
	}
	
	/**
	 * Rozsiri formular o UI prvky
	 * @param IContainer
	 */
	public function extend(IContainer $form, $mask) {
		$classReflection = $mask->classReflection;

		$container = $mask->containerName;
		if ($form->getComponent($container, false)) {
			$container = $form->getComponent($container);
		} else {
			$container = $form->addContainer($container);
		}

		foreach ($mask->fields as $propertyName => $property) {
			unset($ui, $control, $validators, $association);
			// debug($property);
			$ui = $property->ui;
			$control = $container->{'add' . $ui->type}(
				$propertyName,
				$ui->label
			);
			
			
			// ak je control typu selekt a obsahuje definiciu vztahov, pripojim target entitu
			if ($control instanceof Nette\Forms\Controls\SelectBox) {
				$targetEntity = $property->targetEntity;
				if (isset($ui->callback)) {
					// data volane cez callback
					if ($targetEntity->associationType != self::MANY_TO_ONE) {
						throw new \Exception("Nedefinoval si `targetEntity` v {$propertyName} - @" . self::MANY_TO_ONE);
					}
					
					$control->setItems(call_user_func_array($ui->callback, (array) $ui->callbackArgs));
				} elseif (isset($ui->options)) {
					// data volane cez options
					$control->setItems($options);
				} else {
					throw new \Exception("Callback alebo options v `{$classReflection->getConstant('MAIN_ENTITY_NAME')} - {$propertyName}` nie sú validné");
				}
			}
			
		}
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
		$entityReflection = ClassType::from($classReflection->getConstant('MAIN_ENTITY_NAME'));
		
		// poznacim si, ktore entity som v tejto service uz reflektoval
		if (!in_array($entityReflection->getName(), $this->reflectedEntities)) {
			array_push($this->reflectedEntities, $entityReflection->getName());
		}
		return $classReflection;
	}
	
}