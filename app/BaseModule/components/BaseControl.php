<?php
namespace BaseModule\Components;

use Nette\Application\UI\Control;
use Nette\Reflection\ClassType;


abstract class BaseControl extends Control {

	protected function createTemplate($class = NULL) {
		$template = parent::createTemplate($class);

		$helpers = $this->presenter->getContext()->getService('templateHelpers');
		$template->registerHelperLoader(array($helpers, 'loader'));

		$path = dirname(ClassType::from($this)->getFileName()) . '/' . lcfirst( ClassType::from($this)->getShortName() ) . '.latte';

		$template->setTranslator($this->presenter->getContext()->getService('translator'));

		if(is_file($path)) {
			$template->setFile($path); // automatické nastavení šablony
		}


		$template->_imagePipe = $this->presenter->rentalImagePipe;
		$template->rand = rand(1, 1000);
		$template->getCacheOptions = $this->presenter->getCacheOptions;


		return $template;
	}




}
