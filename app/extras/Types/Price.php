<?php

namespace Extras\Types;

class Price extends \Nette\Object {

	const AMOUNT = 'amount';
	const CURRENCY = 'currency';

	const FORMAT_FLOAT = 'float';

	private $amounts = array();
	private $sourceAmount;
	private $sourceCurrency;

	public function __construct($amount, \Entity\Currency $currency) {
		$this->setAmount($amount, $currency);
	}

	public function setAmount($amount, \Entity\Currency $currency) {
		if ($amount === NULL) $amount = 0;

		$this->amounts = array();
		$this->sourceAmount = $amount;
		$this->sourceCurrency = $currency;

		$amounts[$currency->id] = $amount;
	}

	public function isNull() {
		return !($this->sourceAmount > 0);
	}

	public function convertToFloat(\Entity\Currency $currency) {
		return $this->convertTo($currency, self::FORMAT_FLOAT);
	}

	public function convertTo(\Entity\Currency $currency, $format = '%f %s') {
		if (!isset($this->amounts[1])) {
			$this->amounts[1] = $this->sourceAmount / $this->sourceCurrency->exchangeRate;
		}

		$value = $this->amounts[1] * $currency->exchangeRate;

		$this->amounts[$currency->id] = $value;

		if ($format === self::FORMAT_FLOAT) {
			return $value;
		} else {
			return sprintf($format, $value, $this->sourceCurrency->iso);
		}
	}

	public function __toString() {
		return $this->sourceAmount .' '. strtoupper($this->sourceCurrency->iso);
	}

}