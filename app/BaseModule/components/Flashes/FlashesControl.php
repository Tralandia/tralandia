<?php

use Nette\Application\UI\Control,
	Nette\Environment;

class FlashesControl extends Control {

	public function render() {
		//$this->template->setTranslator(Environment::getService('translator'));
		$this->template->setFile(__DIR__ . "/flashes.latte");
		$this->template->flashes = $this->getPresenter()->getTemplate()->flashes;
		return $this->template->render();
	}
}
