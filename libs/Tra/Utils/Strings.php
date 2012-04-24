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

	public static function toSingular($name) {
		if(in_array($name, array('status', 'address', 'decimalPlaces'))) return $name;
		if(Strings::endsWith($name, 'ies')) {
			$name = substr($name, 0 , -3).'y';
		} else if (Strings::endsWith($name, 's')) {
			$name = substr($name, 0 , -1);
		}
		return $name;

	}

}