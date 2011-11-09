<?php

namespace Tra\Reflector;

use Nette;

class Service extends Nette\Object {
	
	public function extend(Nette\Forms\Form $form, $class, $container = null) {
		if ($container !== null) {
			if ($form->getComponent($container, false)) {
				$container = $form->getComponent($container);
			} else {
				$container = $form->addContainer($container);
			}
		} else {
			$container = $form;
		}
		
		$ref = \Nette\Reflection\ClassType::from("\Article");
		
		//debug($ref);
		
		foreach ($ref->getProperties() as $property) {
			//debug($property);
			debug($property->getAnnotations());
			
			if ($property->hasAnnotation('UIControl')) {
				debug($property->getAnnotation('UIControl'));
			}
		}
		
		//$this->em->getRepository('Category')->fetchPairs('id', 'name')
		
		$container->addSelect('category', 'Category', array(99 => 'Name'));
		
	}
}