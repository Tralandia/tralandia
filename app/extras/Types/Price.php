<?php

namespace Extras\Types;

class Price extends BaseType {

	private $amounts = array();
	private $sourceAmount;
	private $sourceCurrency;

	public function __construct($amount, $currency = 1) {
		if ($currency == NULL) {
			$currency = 1;
		}
		$this->setAmount($amount, $currency);
	}

	public function setAmount($amount, $currency = 1) {
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

	public function encode() {
		return \Nette\Utils\Json::encode(array($this->sourceAmount, $this->sourceCurrency));
	}

	public static function decode($value) {
		$value = \Nette\Utils\Json::decode($value);
		return new static($value[0], $value[1]);
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
		return $this->sourceAmount . $this->sourceCurrency;
	}

}