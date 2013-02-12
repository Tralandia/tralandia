<?php

namespace Extras\Forms\Container;

class PhoneContainer extends BaseContainer
{

	public function __construct($label = NULL, $phonePrefixes = NULL)
	{
		parent::__construct();

		$this->addSelect('prefix', NULL, $phonePrefixes);
		$this->addText('number', $label);
	}

	public function getMainControl()
	{
		return $this['number'];
	}
}
