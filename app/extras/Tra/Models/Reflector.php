<?php

namespace Tra\Services;

use Nette;

class Reflector extends Nette\Object {
	
	const ANN_PRIMARY = 'Primary';
	
	const UI_CONTROL = 'UIControl';
	
	protected $service = null;
	protected $reflectedEntities = array();
	
	protected $fields = array();


	public function __construct(IService $service) {
		$this->service = $service;
	}
	
	public function allow($class, array $fields = array()) {		
		$classReflection = $this->getServiceReflection($class);
		
		foreach ($classReflection->getProperties() as $property) {
			if(count($fields) === 0 || in_array($property->name, $fields)) $this->fields[$class][$property->name] = $property;
		}
		
		return $this;
	}
	
	public function except($class, array $fields) {
		foreach ($this->getFields($class) as $field) {
			if(in_array($field->name, $fields)) unset($this->fields[$class][$field->name]);
		}
		
		return $this;
	}


	public function getFields($class) {
		if(!isset($this->fields[$class])) {
			$this->allow($class);
		}
		return $this->fields[$class];
	}
	
	public function extend(Nette\Forms\Form $form, $class) {
		$classReflection = $this->getServiceReflection($class);
		
		$container = $classReflection->getName();
		if ($form->getComponent($container, false)) {
			$container = $form->getComponent($container);
		} else {
			$container = $form->addContainer($container);
		}
				
		foreach ($this->getFields($class) as $property) {
			unset($ui, $control, $validators, $association);

			$annotation = self::UI_CONTROL;
			// ak najdem anotaciu UIControl, vykreslim UI prvok formualra

			if ($property->hasAnnotation($annotation)) {
				$ui = $property->getAnnotation($annotation);

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
					$association = $property->getAnnotation('ManyToOne');
					if (!isset($association->targetEntity)) {
						throw new \Exception("Nedefinoval si `targetEntity` v {$property->getName()} - @ManyToOne");
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
	
	public function getAssocations() {
		$return = array();
		foreach ($this->reflectedEntities as $class) {
			$classReflection = \Nette\Reflection\ClassType::from($class);
			foreach ($classReflection->getProperties() as $property) {
				if ($property->hasAnnotation('ManyToOne')) {
					$association = $property->getAnnotation('ManyToOne');
					$return[$class][$property->getName()] = $association->targetEntity;
				}
			}
		}
		return $return;
	}
	
	public static function getEntityPrimaryData($class) {
		return \Nette\Reflection\ClassType::from($class)->getAnnotation(self::ANN_PRIMARY);
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
		$classReflection = \Nette\Reflection\ClassType::from($class);
		
		// poznacim si, ktore entity som v tejto service uz reflektoval
		if (!in_array($class, $this->reflectedEntities)) {
			array_push($this->reflectedEntities, $classReflection->getName());
		}
		
		return $classReflection;
	}
	
}