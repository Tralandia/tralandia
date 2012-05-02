<?php 
namespace BaseModule\Components;

use Nette\Application\UI\Control;

abstract class BaseControl extends Control {

	protected function createTemplate($class = NULL) {
		$template = parent::createTemplate($class);
		$template->registerHelper('ulList', callback($this->presenter, 'ulListHelper'));
		$template->setTranslator($this->presenter->getService('translator'));
		return $template;
	}

}