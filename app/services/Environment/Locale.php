<?php

namespace Environment;

use Extras\Types\Price;
use Nette\DateTime;

class Locale {

	/**
	 * @var \Environment\Environment
	 */
	protected $environment;

	/**
	 * @var \Environment\Collator
	 */
	protected $collator;

	/**
	 * @var \Environment\NumberFormatter
	 */
	protected $numberFormatter;

	/**
	 * @var array
	 */
	protected $months = [1 => '100020', '100021', '100022', '100023', '100024', '100025', '100026', '100027', '100028', '100029', '100030', '100031'];

	/**
	 * @var array
	 */
	protected $daysLong = [1 => '100046', '100047', '100048', '100049', '100050', '100051', '100052'];

	/**
	 * @var array
	 */
	protected $daysShort = [1 => '100053', '100054', '100055', '100056', '100057', '100058', '100059'];

	/**
	 * @param \Environment\Environment $environment
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
	 * @return \Environment\Collator
	 */
	public function getCollator()
	{
		if(!$this->collator) {
			$this->collator = new Collator($this->getCode());
		}

		return $this->collator;
	}


	/**
	 * @return \Environment\NumberFormatter
	 */
	public function getNumberFormatter()
	{
		if(!$this->numberFormatter) {
			$this->numberFormatter = new NumberFormatter($this->getCode(), NumberFormatter::DECIMAL);
		}

		return $this->numberFormatter;
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
		$amount = $price->getAmount($price->getSourceCurrency());
		$numberFormatter = $this->getNumberFormatter();
		if (is_integer($amount)) {
			$numberFormatter->setAttribute(NumberFormatter::FRACTION_DIGITS, 0);
		} else {
			$numberFormatter->setAttribute(NumberFormatter::FRACTION_DIGITS, 2);
		}
		return $numberFormatter->format($amount) . ' ' . $price->getSourceCurrency()->getIso();
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
	 * @param \DateTime $date
	 *
	 * @return string
	 */
	public function formatDate(\DateTime $date)
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

	/**
	 * @param int $day (1-7)
	 *
	 * @return bool|float|int|mixed|NULL|string
	 */
	public function getDayShort($day)
	{
		return $this->environment->getTranslator()->translate('o' . $this->daysShort[$day]);
	}

	/**
	 * @param int $day (1-7)
	 *
	 * @return bool|float|int|mixed|NULL|string
	 */
	public function getDayLong($day)
	{
		return $this->environment->getTranslator()->translate('o' . $this->daysLong[$day]);
	}
}
