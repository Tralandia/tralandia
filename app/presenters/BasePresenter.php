<?php

use Nette\Application\UI\Presenter,
	Nette\Environment;

abstract class BasePresenter extends Presenter {

	public function beforeRender() {
		//$this->template->setTranslator($this->getService('translator'));
		$this->template->registerHelper('image', callback('Tools::helperImage'));
	}

	protected function createComponentFlashes($name) {
		return new FlashesControl($this, $name);
	}
	
	public function getParams() {
		return (object)$this->getParam();
	}

	public function flashMessage($message, $type = 'info') {
		parent::flashMessage($message, $type);
		$this->getComponent('flashes')->invalidateControl();
	}
	
	public function invalidateFlashMessage() {
		$this->getComponent('flashes')->invalidateControl();
	}
	
	public function getEntityManager() {
		return $this->context->doctrine->entityManager;
	}
	
	public function getEm() {
		return $this->getEntityManager();
	}
}