<?php

use Nette\Application\UI\Form;
use Nette\ObjectMixin;

use Kdyby\Extension\Forms\BootstrapRenderer\BootstrapRenderer;

/**
 * @author Branislav Vaculčiak
 */

abstract class CoolForm extends Form {

	const AJAX_CLASS = 'ajax';

	public function __construct(Nette\Localization\ITranslator $translator = NULL) {
		parent::__construct();

		//$this->setRenderer(new BootstrapRenderer);

		if($translator) {
			$this->setTranslator($translator);
		}

		$this->getElementPrototype()->novalidate = 'novalidate';

		$this->buildForm();
		$this->setDefaultsValues();

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

	protected abstract function setDefaultsValues();


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
//		foreach ($form->getErrors() AS $error) {
//			$this->parent->flashMessage($error, 'error');
//		}
		$this->getParent()->invalidateControl($this->getName());
	}

	public function addSubmit($name, $caption = NULL, $nospam = TRUE) {
		 if ($nospam && !isset($this['nospam'])) {
		 	$noSpam = $this->addHidden('nospam', 'Fill in „nospam“')
		 			->addRule(Form::FILLED, 'You are a spambot!')
		 			->addRule(Form::EQUAL, 'You are a spambot!', 'nospam');

		 	$noSpam->getLabelPrototype()->class('nospam');
		 	$noSpam->getControlPrototype()->class('nospam');
		 }

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

	public function translate()
	{
		$args = func_get_args();
		return call_user_func_array(array($this->getTranslator(), 'translate'), $args);
	}

}
