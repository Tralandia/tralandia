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