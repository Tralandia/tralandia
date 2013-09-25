<?php

namespace Extras\FormMask\Items;

use Nette, Extras, Entity;

/**
 * Address polozka masky
 */
class Address extends Select {

	/**
	 * @param string
	 * @param string
	 * @param Extras\Models\Entity\IEntity
	 * @param Extras\Translator
	 */
	public function __construct($name, $label, Extras\Models\Entity\IEntity $entity, \Tralandia\Localization\Translator $translator) {
		parent::__construct($name, $label, $entity, $translator);
		$this->setValueGetter(new Extras\Callback($entity, $this->getterMethodName($this->name)));
	}

	/**
	 * Vrati hodnoty adresy
	 * @return mixed
	 */
	public function getValue() {
		if (!is_callable($this->getValueGetter())) {
			throw new Nette\InvalidStateException("Nebol zadaný callback gettera hodnot.");
		}

		return $this->getValueGetter()->invoke();
	}

	/**
	 * Ulozi hodnoty adresy
	 * @return mixed
	 */
	public function setValue($value) {
		if (!$address = $this->getValue()) {
			$address = new Entity\Contact\Address;
			$this->entity->{$this->setterMethodName($this->name)}($address);
		}
		$value = (object)$value;
		$address->setRow1($value->row1)
			->setRow2($value->row2)
			->setCity($value->city)
			->setCountry($this->repositoryAccessor->get()->find($value->country))
			->setPostcode($value->postcode);
	}

	/**
	 * Prida polozku do formulara
	 * @param Nette\Forms\Form
	 * @return Nette\Forms\IControl
	 */
	public function extend(Nette\Forms\Form $form) {
		return $form->addAdvancedAddress($this->getName(), $this->getLabel())
			->setAddress($this->getValue())
			->setCountryItems($this->getItems());
	}

	/**
	 * Spracovanie dat z formulara
	 * @param Nette\Forms\Form
	 */
	public function process(Nette\Forms\Form $form) {
		if (!$this->repositoryAccessor) {
			throw new Nette\InvalidStateException("Nebol nasetovany accesor repozitara.");
		}

		$value = $form->getComponent($this->getName())->getValue();
		$this->setValue($value);
	}
}
