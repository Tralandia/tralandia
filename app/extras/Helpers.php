<?php
namespace Extras;

use Environment\Locale;
use Extras\Types\Price;
use Nette;

/**
 * Helpers class
 *
 * @author Dávid Ďurika
 */
class Helpers {

	public $rentalImageDir;

	/**
	 * @var \Environment\Locale
	 */
	private $locale;


	/**
	 * @var \TranslationTexy
	 */
	protected $translationTexy;


	/**
	 * @param \Environment\Locale $locale
	 * @param \TranslationTexy $translationTexy
	 */
	public function __construct(Locale $locale, \TranslationTexy $translationTexy)
	{
		$this->locale = $locale;
		$this->translationTexy = $translationTexy;
	}

	public function loader($helper)
	{
		if (method_exists($this, $helper)) {
			return callback($this, $helper);
		}
	}


	public function texy($s)
	{
		return $this->translationTexy->process($s);
	}


	public function price(Price $price)
	{
		return $this->locale->formatPrice($price);
	}


	/**
	 * Date/time formatting.
	 * @todo tento helper sa neprepisuje Nette uprednostnuje defaultne helpre, cakam na opravu
	 * @param  string|int|DateTime
	 * @param  string
	 * @return string
	 */
	public function date($time)
	{
		if ($time == NULL) { // intentionally ==
			return NULL;
		}

		$time = Nette\DateTime::from($time);
		return $this->locale->formatDate($time);
	}


	/**
	 * Phone formatting.
	 * @param  \Entity\Contact\Phone
	 * @return string
	 */
	public function phone(\Entity\Contact\Phone $phone)
	{
		return $phone->getInternational();
	}


	/**
	 * Date/time formatting.
	 * @param  string|int|DateTime
	 * @param  string
	 * @return string
	 */
	public function dateTime($time)
	{
		if ($time == NULL) { // intentionally ==
			return NULL;
		}

		$time = Nette\DateTime::from($time);
		return $this->locale->formatDateTime($time);
	}


	/**
	 * Date/time formatting.
	 * @param  string|int|DateTime
	 * @param  string
	 * @return string
	 */
	public function time($time)
	{
		if ($time == NULL) { // intentionally ==
			return NULL;
		}

		$time = Nette\DateTime::from($time);
		return $this->locale->formatTime($time);
	}

	/**
	 * Date/time formatting.
	 * @param  string|int|DateTime
	 * @param  string
	 * @return string
	 */
	public function monthName($month)
	{
		if ($month == NULL) { // intentionally ==
			return NULL;
		}

		return $this->locale->getMonth($month);
	}
}
