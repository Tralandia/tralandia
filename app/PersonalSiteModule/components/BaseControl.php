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

		$path = dirname(ClassType::from($this)->getFileName()) . '/' . lcfirst( ClassType::from($this)->getShortName() ) . '.latte';

		if(is_file($path)) {
			$template->setFile($path); // automatické nastavení šablony
		}

		return $template;
	}




}

