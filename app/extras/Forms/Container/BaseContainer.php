<?php

namespace Extras\Forms\Container;

use Nette;

abstract class BaseContainer extends Nette\Forms\Container
{
	abstract public function getMainControl();

	public function getDescription()
	{
		return $this->getMainControl()->getOption('description');
	}

}
