<?php

namespace Tra\Utils;

use Nette;


class Arrays extends \Nette\Utils\Arrays {

	/**
	 * Returns array item or $default if item is not set.
	 * Example: $val = Arrays::get($arr, 'i', 123);
	 * @param  mixed  array
	 * @param  mixed  key
	 * @param  mixed  default value
	 * @return mixed
	 */
	public static function get($arr, $key, $default = NULL)
	{
		if(!(is_array($arr) || $arr instanceof Nette\ArrayHash)) {
			throw new \Nette\InvalidArgumentException('Argument $arr does not match with the expected value');
		}

		foreach (is_array($key) ? $key : array($key) as $k) {
			if ((is_array($arr) || $arr instanceof Nette\ArrayHash) && array_key_exists($k, $arr)) {
				$arr = $arr[$k];
			} else {
				if (func_num_args() < 3) {
					throw new Nette\InvalidArgumentException("Missing item '$k'.");
				}
				return $default;
			}
		}
		return $arr;
	}

}