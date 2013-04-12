<?php

namespace Extras\Forms\Container;

use Extras\Types\Price;
use Nette\Localization\ITranslator;

class PriceContainer extends BaseContainer
{

	public function __construct($label = NULL, $currencies = NULL, ITranslator $translator)
	{
		parent::__construct();

		$this->addText('amount', $label)
			->setOption('help', $translator->translate('o100073'));
		$this->addSelect('currency', NULL, $currencies);
	}

	public function setValues($values, $erase = FALSE)
	{
		if(!$values) return NULL;

		if($values instanceof Price) {
			$valuesTemp = [];
			$valuesTemp['amount'] = $values->getSourceAmount();
			$valuesTemp['currency'] = $values->getSourceCurrency()->getId();
			$values = $valuesTemp;
		}
		parent::setValues($values, $erase);
	}


	public function getMainControl()
	{
		return $this['amount'];
	}
}
