<?php

namespace Extras\FormMask;

use Entity\BaseEntity;
use Nette, Extras;

/**
 * Tovarnicka dynamickeho formulara
 */
class FormFactory {

	/** @var Generator */
	private $generator;


	/**
	 * @param Generator $generator
	 */
	function __construct(Generator $generator) {
		$this->generator = $generator;
	}


	/**
	 * Vytvorenie formulara
	 *
	 * @param \Entity\BaseEntity $entity
	 *
	 * @return \Nette\Application\UI\Form
	 */
	public function create(BaseEntity $entity) {
		$form = new Nette\Application\UI\Form;
		$form->setRenderer(new Extras\Forms\Rendering\DefaultRenderer);
		$form->onSuccess[] = array($this->generator->getMask(), 'process');
		$this->generator->setEntity($entity)->build();
		$this->generator->getMask()->extend($form);
		return $form;
	}
}
