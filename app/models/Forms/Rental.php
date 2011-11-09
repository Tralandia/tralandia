<?php

namespace Forms;

use Nette\Forms\Form,
	Nette\Application as NA,
	Nette\Diagnostics\Debugger;

class Rental extends \CoolForm {

    public function __construct(\Nette\ComponentModel\IContainer $parent = null, $name = null) {
		parent::__construct($parent, $name);


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
		
		$this->addSelect('category', 'Category', array(99 => 'Name'));
		
		$s = new RentalService;
		
		$s->extendsFormFromEnity($this);
		
		
		
		$this->addSubmit('save', 'Save');
		$this->onSuccess[] = callback($this, 'onSave');
	}

	public function onSave(Form $form) {
		$values = $form->getValues();
		
		debug($values);
    }
}
