<?php 
namespace BaseModule\Components;

use Nette\Application\UI\Control;

abstract class BaseControl extends Control {

	protected function createTemplate($class = NULL) {
		$template = parent::createTemplate($class);
		// $template->setTranslator($this->presenter->getService('translator'));
		return $template;
	}


}