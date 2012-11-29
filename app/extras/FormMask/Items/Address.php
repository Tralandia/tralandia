<?php

namespace Extras\FormMask\Items;

use Nette, Extras;

/**
 * Address polozka masky
 */
class Address extends Base {

	/** @var Extras\Models\Repository\RepositoryAccessor */
	protected $repository;

	/**
	 * @param string
	 * @param string
	 * @param Extras\Models\Entity\IEntity
	 * @param Extras\Models\Repository\RepositoryAccessor
	 */
	public function __construct($name, $label, Extras\Models\Entity\IEntity $entity, Extras\Models\Repository\RepositoryAccessor $repository) {
		$this->name = $name;
		$this->label = $label;
		$this->entity = $entity;
		$this->repository = $repository;

		$this->setValueGetter(new Extras\Callback($entity, $this->getterMethodName($this->name)));
		$this->setValueSetter(new Extras\Callback($entity, $this->setterMethodName($this->name)));

		//$this->setValueGetter(new Extras\Callback($this, 'getChildrenValue'));
		//$this->setValueSetter(new Extras\Callback($this, 'setChildrenValue'));
	}

	/**
	 * Prida polozku do formulara
	 * @param Nette\Forms\Form
	 * @return Nette\Forms\IControl
	 */
	public function extend(Nette\Forms\Form $form) {
		debug($this->getValue());

		
		return $form->addAdvancedAddress($this->getName(), $this->getLabel())
			->setDefaultValue($this->getValue());
	}
}