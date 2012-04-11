<?php 
namespace FrontModule\BasePage;

use Nette\Application\UI\Control;

abstract class BasePage extends Control {

	public function render() {
		$template = $this->template;
		$template->setFile(dirname(__FILE__) . '/page.latte');
		$template->setTranslator($this->presenter->getService('translator'));
		$template->render();
	}

}