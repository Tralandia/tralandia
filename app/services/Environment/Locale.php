<?php

namespace Environment;

use Extras\Types\Price;
use Nette\DateTime;

class Locale {

	/**
	 * @var Environment
	 */
	protected $environment;

	/**
	 * @var Collator
	 */
	protected $collator;

	/**
	 * @var array
	 */
	protected $months = [1 => '100020', '100021', '100022', '100023', '100024', '100025', '100026', '100027', '100028', '100029', '100030', '100031'];

	/**
	 * @param Environment $environment
	 */
	public function __construct(Environment $environment)
	{
		$this->environment = $environment;
	}

	/**
	 * @return string
	 */
	public function getCode()
	{
		$environment = $this->environment;
		return $environment->getLanguage()->getIso() . '_' . strtoupper($environment->getPrimaryLocation()->getIso());
	}

	/**
	 * @return string
	 */
	public function getGooglePlusLangCode()
	{
		$currentCode = $this->getCode();
		$languageCodes = array('zh-HK', 'zh-CN', 'zh-TW', 'en-GB', 'en-US', 'fr-CA', 'pt-BR', 'pt-PT', 'es-419');
		if (in_array($currentCode, $languageCodes)) {
			return $currentCode;
		} else {
			return substr($currentCode, 0, 2);
		}
	}

	/**
	 * @return Collator
	 */
	public function getCollator()
	{
		if(!$this->collator) {
			$this->collator = new Collator($this->getCode());
		}

		return $this->collator;
	}



	/********************* number tools *********************/


	/**
	 * @return string
	 */
	public function getPriceFormat()
	{
		return '%f %s';
	}


	/**
	 * @param \Extras\Types\Price $price
	 *
	 * @return string
	 */
	public function formatPrice(Price $price)
	{
		return $price->convertTo($price->getSourceCurrency(), $this->getPriceFormat());
	}


	/********************* date tools *********************/



	/**
	 * @return string
	 */
	public function getDateFormat()
	{
		return 'Y-m-d';
	}

	/**
	 * @return string
	 */
	public function getTimeFormat()
	{
		return 'H:i';
	}

	/**
	 * @return string
	 */
	public function getDateTimeFormat()
	{
		return $this->getDateFormat() . ' ' . $this->getTimeFormat();
	}

	/**
	 * @param \Nette\DateTime $date
	 *
	 * @return string
	 */
	public function formatDate(DateTime $date)
	{
		return $date->format($this->getDateFormat());
	}

	/**
	 * @param \Nette\DateTime $date
	 *
	 * @return string
	 */
	public function formatTime(DateTime $date)
	{
		return $date->format($this->getTimeFormat());
	}

	/**
	 * @param \Nette\DateTime $date
	 *
	 * @return string
	 */
	public function formatDateTime(DateTime $date)
	{
		return $date->format($this->getDateTimeFormat());
	}

	/**
	 * @param int $month
	 *
	 * @return bool|float|int|mixed|NULL|string
	 */
	public function getMonth($month)
	{
		return $this->environment->getTranslator()->translate('o' . $this->months[$month]);
	}

}