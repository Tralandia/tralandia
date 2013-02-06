<?php

namespace Extras\Forms\Container;

class PriceContainer extends BaseContainer
{

	public function __construct($label = NULL, $currencies = NULL)
	{
		parent::__construct();

		$this->addText('amount', $label);
		$this->addSelect('currency', NULL, $currencies);
	}

	public function getMainControl()
	{
		return $this['amount'];
	}
}
