<?php

use Nette\Environment,
	Nette\Application\UI\Form,
	Nette\ObjectMixin;

use Kdyby\Extension\Forms\BootstrapRenderer\BootstrapRenderer;

/**
 * @author Branislav Vaculčiak
 */

abstract class CoolForm extends Form {

	const AJAX_CLASS = 'ajax';

	public function __construct(\Nette\ComponentModel\IContainer $parent = NULL, $name = NULL) {
		parent::__construct($parent, $name);

		//$this->setRenderer(new BootstrapRenderer);

		//$this->setTranslator($translator);

		$this->getElementPrototype()->novalidate = 'novalidate';

		$this->onError[] = callback($this, 'onInvalid');

		# @todo brano toto je tu naco? ja som zrusil tu metodu getParam 
		// if ($this->getParam('invalidate', false)) {
		// 	$this->valid = FALSE;
		// }
	
	}


	/**
	 * Abstract function which handles the form creation.
	 * @abstract
	 * @return void
	 */
	protected abstract function buildForm();


	public function ajax() {
		$this->getElementPrototype()->addClass(self::AJAX_CLASS);
	}

	public function &__get($name) {
		$com = $this->getComponent($name, false);
		if ($com !== null) {
			return $com;
		} else {
			return ObjectMixin::get($this, $name);
		}
	}

	public function onInvalid(Form $form) {
		// @todo dorobit
		// if ($this->getParam('invalidate', false)) {
		// 	//$form->cleanErrors();
		// 	$this->parent->invalidateControl($this->getName());
		// } else {
		// 	foreach ($form->getErrors() AS $error) {
		// 		$this->parent->flashMessage($error, 'error');
		// 	}
		// }
		foreach ($form->getErrors() AS $error) {
			$this->parent->flashMessage($error, 'error');
		}
	}

	public function addSubmit($name, $caption = NULL, $nospam = TRUE) {
		// @todo dorobit
		// if ($nospam && !isset($this['nospam'])) {
		// 	$noSpam = $this->addHidden('nospam', 'Fill in „nospam“')
		// 			->addRule(Form::FILLED, 'You are a spambot!')
		// 			->addRule(Form::EQUAL, 'You are a spambot!', 'nospam');

		// 	$noSpam->getLabelPrototype()->class('nospam');
		// 	$noSpam->getControlPrototype()->class('nospam');
		// }

		return parent::addSubmit($name, $caption);
	}

	public function getValues($asArray = FALSE) {
		$values = parent::getValues($asArray);
		unset($values['nospam']);
		return $values;
	}

	public function getHtmlId() {
		return $this->getElementPrototype()->getId();
	}

}
