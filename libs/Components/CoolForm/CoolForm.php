<?php

use Nette\Environment,
	Nette\Application\UI\Form,
	Nette\ObjectMixin;

/**
 * @author Branislav Vaculčiak
 */

class CoolForm extends Form {

	const AJAX_CLASS = 'ajax';

	public function __construct(\Nette\ComponentModel\IContainer $parent = NULL, $name = NULL) {
		parent::__construct($parent, $name);

		// $this->addProtection('Validity expired form.');
		//$this->setTranslator(Environment::getService('translator'));
		$this->onError[] = callback($this, 'onInvalid');
		if ($this->getParam('invalidate', false)) {
			$this->valid = FALSE;
		}
	}

	public function ajax($flag = true) {
		$flag ? $this->getElementPrototype()->class(self::AJAX_CLASS) : NULL;
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
		if ($this->getParam('invalidate', false)) {
			$form->cleanErrors();
			$this->parent->invalidateControl($this->getName());
		} else {
			foreach ($form->getErrors() AS $error) {
				$this->parent->flashMessage($error, 'error');
			}
		}
	}

	public function getUser() {
		return $this->em->find('User', $this->parent->user->id);
	}

	public function getHtmlId() {
		return $this->getElementPrototype()->getId();
	}

	public function getParam($key, $default = null) {
		return $this->parent->getParam($key, $default);
	}

	public function flashMessage($message, $type = 'info') {
		$this->parent->flashMessage($message, $type);
	}
}
