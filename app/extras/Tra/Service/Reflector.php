<?php

namespace Tra\Services;

use Nette;

class Reflector extends Nette\Object {
	
	protected $service = null;
	
	public function __construct(IService $service) {
		$this->service = $service;
	}
	
	public function extend(Nette\Forms\Form $form, $class) {
		$classReflection = \Nette\Reflection\ClassType::from($class);

		$container = $classReflection->getName();
		if ($form->getComponent($container, false)) {
			$container = $form->getComponent($container);
		} else {
			$container = $form->addContainer($container);
		}
		
		foreach ($classReflection->getProperties() as $property) {
			unset($ui, $control, $validators, $association);

			// ak najdem anotaciu UIControl, vykreslim UI prvok formualra
			if ($property->hasAnnotation('UIControl')) {
				$ui = $property->getAnnotation('UIControl');

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
					$control->setItems($callback($association->targetEntity, isset($ui->value) ? $ui->value : $property->getName()));
				} elseif (isset($ui->options) && $options = $this->getOptions($classReflection, $ui->options)) {
					// data volane cez options
					if (!isset($ui->options) && !isset($ui->callback)) {
						throw new \Exception("Nedefinoval si `options` alebo `callback` v {$property->getName()} - @UIControl");
					}
					$control->setItems($options);
				} else {
					throw new \Exception("Callback alebo options nie sú validné");
				}
			}
			
			//debug($property->getAnnotation('Validator'));
		}
	}
	
	public function getAssocationColumns($class) {
		$classReflection = \Nette\Reflection\ClassType::from($class);
		$columns = array();
		foreach ($classReflection->getProperties() as $property) {
			if ($property->hasAnnotation('ManyToOne')) {
				$association = $property->getAnnotation('ManyToOne');
				$columns[$property->getName()] = $association->targetEntity;
			}
		}
		return $columns;
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
}