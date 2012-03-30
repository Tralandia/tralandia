<?php

namespace Tra\Utils;

class Strings extends \Nette\Utils\Strings {

	/**
	 * Convert first character to lower case.
	 * @param  string  UTF-8 encoding
	 * @return string
	 */
	public static function firstLower($s)
	{
		return self::lower(self::substring($s, 0, 1)) . self::substring($s, 1);
	}


}