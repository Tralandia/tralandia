<?php 
namespace BaseModule\Components;

use Nette\Application\UI\Control;
use Nette\Reflection\ClassType;


abstract class BaseControl extends Control {

	protected function createTemplate($class = NULL) {
		$template = parent::createTemplate($class);

		$path = dirname(ClassType::from($this)->getFileName()) . '/' . lcfirst( ClassType::from($this)->getShortName() ) . '.latte';
		$template->setFile($path); // automatické nastavení šablony

		// $template->setTranslator($this->presenter->getService('translator'));

		return $template;
	}


}