<?php
/**
 * This file is part of the tralandia.
 * User: macbook
 * Created at: 08/04/14 14:17
 */

namespace Tralandia\RentalSearch;


use Nette;

class GpsHelper
{

	const PRECISION = 100;
	const ROUND_UP_TO = 5;


	/**
	 * @param $coordinate
	 *
	 * @return int
	 */
	public static function coordinateToKey($coordinate)
	{
		$coordinate = $coordinate * self::PRECISION; # 42.543 -> 4254.3
		$coordinate = self::roundUpToAny($coordinate, self::ROUND_UP_TO); # 4254.3 -> 4255
		return (int) $coordinate;
	}


	/**
	 * @param $n
	 * @param $x
	 *
	 * @return float
	 */
	public static function roundUpToAny($n,$x) {
		return round(($n+$x/2)/$x)*$x;
	}

}
