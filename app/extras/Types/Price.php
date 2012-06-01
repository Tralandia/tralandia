<?php

namespace Extras\Types;

class Price extends \Nette\Object {

	const AMOUNT = 'amount';
	const CURRENCY = 'currency';

	private $amounts = array();
	private $sourceAmount;
	private $sourceCurrency;

	public function __construct($amount, $currency = 1) {
		if(is_array($amount)) {
			$data = $amount;
			$amount = array_shift($data);
			if ($amount === NULL) $amount = 0;
			$currency = array_shift($data);
		}

		$this->setAmount($amount, $currency);
	}

	public function setAmount($amount = 0, $currency = 1) {
		if ($amount === NULL) $amount = 0;

		$this->amounts = array();
		if (is_numeric($currency) && $currency > 0) {
			$currencyId = (int)$currency;
		} else if ($currency instanceof \Extras\Models\Entity || $currency instanceof \Extras\Models\Service) {
			$currencyId = $currency->id;
		} else {
			// debug(1, $currency);
			throw new \Nette\UnexpectedValueException('$currency is not an ID or Entity or Service');
		}
		$this->sourceAmount = $amount;
		$this->sourceCurrency = $currencyId;

		$amounts[(int)$currency] = $amount;
	}

	public function getIn($currency) {
		if ($currency instanceof \Extras\Models\Entity || $currency instanceof \Extras\Models\Service) {
		} else {
			// debug(2, $currency);
			throw new \Nette\UnexpectedValueException('$currency is not an ID or Entity or Service');
		}

		$currencyId = $currency->id;

		if (isset($this->amounts[$currencyId])) {
			return $this->amounts[$currencyId];
		} else {
			return $this->convertTo($currency);
		}
	}

	public function toArray() {
		return array(
			self::AMOUNT => $this->sourceAmount, 
			self::CURRENCY => $this->sourceCurrency
		);
	}

	public function encode() {
		return \Nette\Utils\Json::encode($this->toArray());
	}

	public static function decode($value) {
		if(!$value) {
			$value = \Nette\Utils\Json::decode($value, true);
			return new static($value);
		} else {
			return new static();
		}
	}

	private function convertTo($currency) {
		$currencyId = $currency->id;

		if (!$this->amounts[1]) {
			$this->amounts[1] = $sourceAmount / $currency->exchangeRate;
		}

		$value = $this->amounts[1] * $currency->exchangeRate;

		$this->amounts[$currencyId] = $value;
		return $value;
	}

	public function __toString() {
		return '';
		return $this->sourceAmount . $this->sourceCurrency;
	}

}