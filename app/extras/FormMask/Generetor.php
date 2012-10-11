<?php

namespace Extras\FormMask;

use Nette, Extras;

class Generator extends Nette\Object {

	protected $entity;

	protected $entityReflection;

	protected $mask;

	protected $configurator;

	protected $factories = array();

	public function __construct(Extras\FormMask\Mask $mask, Extras\Config\Configurator $configurator, Extras\Models\Entity\IEntity $entity) {
		$this->entity = $entity;
		$this->entityReflection = Extras\Reflection\Entity\ClassType::from($entity);
		$this->configurator = $configurator;
		$this->mask = $mask;	
	}


	public function build() {

		foreach ($this->configurator->getForm() as $field) {
			$item = $this->factories[$field->getType()]->create($field->getName(), $field->getLabel(), $this->entity->name);
			$item->setValueGetter(new Extras\Callback($this->entity, $this->getterMethodName($field->getName()), array($this->entity)));
			$item->setValueSetter(new Extras\Callback($this->entity, $this->setterMethodName($field->getName()), array($this->entity)));
			$this->mask->addItem($item);
		}
		$this->mask->add(Extras\FormMask\Mask::SUBMIT, 'submit', 'Odoslať');
	}

	private function setterMethodName($name) {
		return 'set' . ucfirst($name);
	}

	private function getterMethodName($name) {
		return 'get' . ucfirst($name);
	}


	public function setItemPhrase(Extras\FormMask\Items\Foctories\PhraseFactory $factory) {
		$this->factories['phrase'] = $factory;
	}

	public function setItemText(Extras\FormMask\Items\Foctories\TextFactory $factory) {
		$this->factories['text'] = $factory;
	}
}