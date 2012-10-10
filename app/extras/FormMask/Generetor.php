<?php

namespace Extras\FormMask;

use Nette, Extras;

class Generator extends Nette\Object {

	protected $entity;

	protected $entityReflection;

	protected $mask;

	protected $configurator;

	public function __construct(Extras\FormMask\Mask $mask, Extras\Config\Configurator $configurator, Extras\IEntity $entity) {
		$this->entity = $entity;
		$this->entityReflection = Extras\Reflection\Entity\ClassType::from($entity);
		$this->configurator = $configurator;
		$this->mask = $mask;
		

		//debug($this->entityReflection);	
	}


	public function build() {
		foreach ($this->configurator->getForm() as $field) {

			$item = $this->mask->add(constant('Extras\\FormMask\\Mask::' . $field->getType()), $field->getName(), $field->getLabel());

			$item->setValueGetter(new Extras\Callback($this->entity, $this->getterMethodName($field->getName()), array($this->entity)));
			$item->setValueSetter(new Extras\Callback($this->entity, $this->setterMethodName($field->getName()), array($this->entity)));
		}
		$this->mask->add(Extras\FormMask\Mask::SUBMIT, 'submit', 'Odoslať');
	}

	private function setterMethodName($name) {
		return 'set' . ucfirst($name);
	}

	private function getterMethodName($name) {
		return 'get' . ucfirst($name);
	}
}