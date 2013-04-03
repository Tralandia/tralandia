<?php

namespace Extras\Types;

class Price extends \Nette\Object {

	const AMOUNT = 'amount';
	const CURRENCY = 'currency';

	const FORMAT_FLOAT = 'float';

	const DEFAULT_CURRENCY = 'defaultCurrency';

	protected $amounts = array();
	protected $sourceAmount;
	protected $sourceCurrency;

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


	/**
	 * @return bool
	 */
	public function isNull() {
		return !($this->sourceAmount > 0);
	}

	public function getSourceAmount()
	{
		return $this->sourceAmount;
	}

	/**
	 * @return \Entity\Currency
	 */
	public function getSourceCurrency()
	{
		return $this->sourceCurrency;
	}

	/**
	 * @param \Entity\Currency $currency
	 *
	 * @return string
	 */
	public function convertToFloat(\Entity\Currency $currency) {
		return $this->convertTo($currency, self::FORMAT_FLOAT);
	}

	/**
	 * @param \Entity\Currency $currency
	 * @param string $format
	 *
	 * @return string
	 */
	public function convertTo(\Entity\Currency $currency, $format = '%f %s') {
		if(!isset($this->amounts[$currency->getId()])) {
			if (!isset($this->amounts[1])) {
				if ($this->sourceCurrency->exchangeRate == 0) {
					$this->amounts[1] = 0;
				} else {
					$this->amounts[1] = $this->sourceAmount / $this->sourceCurrency->exchangeRate;
				}
			}

			$value = $this->amounts[1] * $currency->getExchangeRate();

			$this->amounts[$currency->getId()] = $value;
		} else {
			$value = $this->amounts[$currency->getId()];
		}

		if ($format === self::FORMAT_FLOAT) {
			return $value;
		} else {
			return sprintf($format, $value, $currency->getIso());
		}
	}

	/**
	 * @return string
	 */
	public function __toString() {
		return $this->sourceAmount .' '. strtoupper($this->sourceCurrency->iso);
	}

}
