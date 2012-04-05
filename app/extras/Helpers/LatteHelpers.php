<?php

namespace Extras\Helpers;

class LatteHelpers extends \Nette\Object {

	public static function ulList($data, $columnCount = 3, $li = NULL) {
		if(!($data instanceof Traversable || is_array($data))) {
			throw new \Nette\InvalidArgumentException('Argument "$data" does not match with the expected value');
		}

		if(!is_numeric($columnCount) || $columnCount <= 0) {
			throw new \Nette\InvalidArgumentException('Argument "$columnCount" does not match with the expected value');
		}

		if($li === NULL) {
			$li = '<li>%name% - {_123}</li>';
		}

		$search = array();
		foreach (reset($data) as $key => $value) {
			$search[] = "%$key%";
		}

		$newData = array();
		$dataTemp = $data;
		while(count($dataTemp)) {
			for ($i=0; $i < $columnCount; $i++) {
				if(!count($dataTemp)) break 2;
				$liTemp = str_replace($search, array_shift($dataTemp), $li);
				$newData[$i][] = $liTemp;
			}
		}

		$return = array();
		foreach ($newData as $key => $value) {
			$return[] = '<ul>'.implode('', $value).'</ul>';
		}

		return implode('', $return);
	}
}