<?php
namespace Extras;

use Environment\Locale;
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
	 * @param \Environment\Locale $locale
	 */
	public function __construct(Locale $locale)
	{
		$this->locale = $locale;
	}

	public function loader($helper)
	{
		if (method_exists($this, $helper)) {
			return callback($this, $helper);
		}
	}


	public function rentalImageSrc($image, $size = 'thumbnail')
	{
		if (is_object($image)) {
			return $this->rentalImageDir . $image->filePath . DIRECTORY_SEPARATOR . $size . '.jpeg';		
		} else {
			return $image;
		}
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

}