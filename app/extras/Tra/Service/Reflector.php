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
			if ($property->hasAnnotation('UIControl')) {
				$ui = $property->getAnnotation('UIControl');
				
				//debug($control);
		
				$control = $container->{'add' . ucfirst($ui->type)}(
					$property->getName(),
					isset($ui->label) ? $ui->label : ucfirst($property->getName())
				);
			}
			
			if (isset($control) && $property->hasAnnotation('Validator')) {
				debug($control);
				
				
				$validators = $property->getAnnotations();
				$validators = $validators['Validator'];
				
				//debug($validators);
			}
			
			//debug($property->getAnnotation('Validator'));
		}
		
		//$this->em->getRepository('Category')->fetchPairs('id', 'name')
		
	//	$container->addSelect('category', 'Category', array(99 => 'Name'));
		
	}
	
	
}