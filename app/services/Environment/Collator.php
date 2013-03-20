<?php

namespace Environment;

use Nette;

/**
 * Collator class
 *
 * @author Dávid Ďurika
 */
class Collator extends \Collator
{

	public function asortByKey(array &$array, $keyName, $sort_flag = null)
	{
		$temp = [];
		foreach($array as $key => $value) {
			$temp[$key] = Nette\Utils\Arrays::get($value, $keyName, NULL);
		}

		$this->asort($temp, $sort_flag);

		$final = [];
		foreach($temp as $key => $value) {
			$final[$key] = $array[$key];
		}

		$array = $final;
	}

	public function ksort(array &$array)
	{
		$keys = array_keys($array);
		$this->sort($keys);
		$temp = array();
		foreach ($keys as $value) {
			$temp[$value] = $array[$value];
		}
		$array = $temp;
	}

}
