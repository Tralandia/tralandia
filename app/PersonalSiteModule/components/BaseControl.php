<?php
/**
 * This file is part of the tralandia.
 * User: macbook
 * Created at: 05/03/14 14:07
 */

namespace PersonalSiteModule;

use Nette\Application\UI\Control;
use Nette\Reflection\ClassType;


abstract class BaseControl extends Control {

	protected function createTemplate($class = NULL)
	{
		$template = parent::createTemplate($class);

		$helpers = $this->presenter->getContext()->getService('templateHelpers');
		$template->registerHelperLoader(array($helpers, 'loader'));

		$path = dirname(ClassType::from($this)->getFileName()) . '/' . lcfirst( ClassType::from($this)->getShortName() ) . '.latte';

		$template->setTranslator($this->presenter->getContext()->getService('translator'));

		if(is_file($path)) {
			$template->setFile($path); // automatické nastavení šablony
		}

		$template->_imagePipe = $this->presenter->rentalImagePipe;

		return $template;
	}




}

