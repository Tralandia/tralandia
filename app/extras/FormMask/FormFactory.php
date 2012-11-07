<?php

namespace Extras\FormMask;

use Nette, Extras;

/**
 * Tovarnicka dynamickeho formulara
 */
class FormFactory {

	/** @var Generator */
	private $generator;

	/**
	 * @param Genrator
	 */
	function __construct(Generator $generator) {
		$this->generator = $generator;
	}

	/**
	 * Vytvorenie formulara
	 * @param Extras\Models\Entity\IEntity
	 * @return CoolForm
	 */
	public function create(Extras\Models\Entity\IEntity $entity) {
		$form = new Nette\Application\UI\Form;

		$this->generator->setEntity($entity)->build();
		$this->generator->getMask()->extend($form);
		
		return $form;
	}
}