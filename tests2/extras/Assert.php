<?php

namespace Extras;

class Assert extends \Assert {



	public static function different($expected, $actual) {
		if ($actual === $expected) {
			self::log($expected, $actual);
			self::doFail('Failed asserting that ' . self::dump($actual) . ' is NOT identical to expected ' . self::dump($expected));
		}
	}

}