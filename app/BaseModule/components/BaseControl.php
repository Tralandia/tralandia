<?php
namespace BaseModule\Components;

use Nette\Application\UI\Control;
use Nette\Reflection\ClassType;


abstract class BaseControl extends Control {

	protected function createTemplate($class = NULL) {
		$template = parent::createTemplate($class);

		$path = dirname(ClassType::from($this)->getFileName()) . '/' . lcfirst( ClassType::from($this)->getShortName() ) . '.latte';

		$template->setTranslator($this->presenter->getService('translator'));

		if(is_file($path)) {
			$template->setFile($path); // automatické nastavení šablony
		}

		return $template;
	}




}
