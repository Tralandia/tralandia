<?php

namespace Extras\Models;

use Nette,
	Nette\ArrayHash,
	Nette\Reflection\ClassType,
	Nette\Reflection\Property,
	Nette\Utils\Strings,
	Nette\ComponentModel\IContainer;

class Reflector extends Nette\Object {
	
	const ANN_PRIMARY = 'UI\Primary';
	const UI_CONTROL = 'UI\Control';

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
	public function getMask() {
		$mask = array();
		$assocations = $this->getAssocations();
		//$collection = $this->getCollections();

		//debug($assocations, $this->getFields($this->service));
//debug($this->getFields($this->service));

		$classReflection = $this->getServiceReflection($this->service);
		$classReflection = ClassType::from($classReflection->getConstant('MAIN_ENTITY_NAME'));

		foreach ($this->getFields($this->service) as $property) {
			if ($pmask = $this->getPropertyMask($property, $classReflection)) {
				$mask[] = $pmask;
			}
			
			//debug($options);
		}

		return $mask;
	}

	/**
	 * Vrati masku pre ziskanie dat pre vsupnu property
	 * @return array
	 */
	public function getPropertyMask(Property $property, ClassType $entity) {
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
			}
		} elseif ($property->hasAnnotation(self::ONE_TO_MANY) || $property->hasAnnotation(self::MANY_TO_MANY)) {
			$options->type = $property->hasAnnotation(self::ONE_TO_MANY)
				? self::ONE_TO_MANY
				: self::MANY_TO_MANY;
			// TODO: dokoncit
		}

		return ArrayHash::from($options);
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
	
	public function allow($class, array $fields = array()) {		
		$classReflection = $this->getServiceReflection($class);
		$classReflection = ClassType::from($classReflection->getConstant('MAIN_ENTITY_NAME'));

		foreach ($classReflection->getProperties() as $property) {
			if(count($fields) === 0 || in_array($property->name, $fields))
				$this->fields[$class][$property->name] = $property;
		}
		
		return $this;
	}
	
	/**
	 * 
	 */
	public function except($class, array $fields) {
		foreach ($this->getFields($class) as $field) {
			if(in_array($field->name, $fields)) unset($this->fields[$class][$field->name]);
		}
		
		return $this;
	}

	/**
	 * Vrati zoznam vlastnoti o ktore sa bude rozsirovat
	 * @param string
	 */
	protected function getFields($class) {
		if(!isset($this->fields[$class])) {
			$this->allow($class);
		}
		return $this->fields[$class];
	}
	
	/**
	 * Rozsiri formular o UI prvky
	 * @param IContainer
	 */
	public function extend(IContainer $form) {
		$classReflection = $this->getServiceReflection($this->service);

		$container = $this->getContainerName();
		if ($form->getComponent($container, false)) {
			$container = $form->getComponent($container);
		} else {
			$container = $form->addContainer($container);
		}

		foreach ($this->getFields($this->service) as $property) {
			unset($ui, $control, $validators, $association);

			// ak najdem anotaciu UIControl, vykreslim UI prvok formualra
			if ($property->hasAnnotation(self::UI_CONTROL)) {
				$ui = $property->getAnnotation(self::UI_CONTROL);

				$control = $container->{'add' . ucfirst($ui->type)}(
					$property->getName(),
					isset($ui->label) ? $ui->label : ucfirst($property->getName())
				);
			}
			
			// ak najdem anotacie validacie
			if (isset($control) && $property->hasAnnotation('Validator')) {
				$validators = $property->getAnnotations();
				$validators = $validators['Validator'];
				
				//debug($validators);
				// tu este budu validatory
			}
			
			// ak je control typu selekt a obsahuje definiciu vztahov, pripojim target entitu
			if (isset($ui) && $control instanceof Nette\Forms\Controls\SelectBox) {
				if (isset($ui->callback) && $callback = $this->getCallback($classReflection, $ui->callback)) {
					// data volane cez callback
					$association = $property->getAnnotation(self::MANY_TO_ONE);
					if (!isset($association->targetEntity)) {
						throw new \Exception("Nedefinoval si `targetEntity` v {$property->getName()} - @" . self::MANY_TO_ONE);
					}

					$primaryValue = $this->getEntityPrimaryData($association->targetEntity)->value;
					$primaryKey = $this->getEntityPrimaryData($association->targetEntity)->key;
					
					$control->setItems($callback($association->targetEntity, $primaryKey, isset($primaryValue) ? $primaryValue : $primaryKey));
				} elseif (isset($ui->options) && $options = $this->getOptions($classReflection, $ui->options)) {
					// data volane cez options
					if (!isset($ui->options) && !isset($ui->callback)) {
						throw new \Exception("Nedefinoval si `options` alebo `callback` v {$property->getName()} - @UIControl");
					}
					$control->setItems($options);
				} else {
					throw new \Exception("Callback alebo options v `{$class} - {$property->getName()}` nie sú validné");
				}
			}
			
			//debug($property->getAnnotation('Validator'));
		}
	}
	
	/**
	 * Vrati asociacne vztahy (typu ManyToOne)
	 */
	public function getAssocations() {
		$return = array();
		foreach ($this->reflectedEntities as $class) {
			$classReflection = ClassType::from($class);
			foreach ($classReflection->getProperties() as $property) {
				if ($property->hasAnnotation(self::MANY_TO_ONE)) {
					$association = $property->getAnnotation(self::MANY_TO_ONE);
					$return[$class][$property->getName()] = $association->targetEntity;
				}
			}
		}
		return $return;
	}
	
	/**
	 * Pripravi data
	 */
	public static function getEntityPrimaryData($class) {
		return ClassType::from($class)->getAnnotation(self::ANN_PRIMARY);
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
	
	private function getCallback($classReflection, $callback) {
		//list($object, $method) = explode(',', $callback);
		return callback($this->service, trim($callback));
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