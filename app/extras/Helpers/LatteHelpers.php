<?php

namespace Extras\Helpers;

class LatteHelpers extends \Nette\Object {

	public static function ulList($data, $columnCount = 3, $li = NULL) {
		
		if(!($data instanceof \Traversable || is_array($data))) {
			throw new \Nette\InvalidArgumentException('Argument "$data" does not match with the expected value');
		}

		if(!is_numeric($columnCount) || $columnCount <= 0) {
			throw new \Nette\InvalidArgumentException('Argument "$columnCount" does not match with the expected value');
		}

		if($li === NULL) {
			$li = '<li>%name% - {_123}</li>';
		}

		preg_match_all('/%[a-zA-Z,]+%/', $li, $matches);

		$replaces = array();
		foreach ($matches[0] as $match) {
			if (gettype($data)=='object') {
				$value = '$item->'.str_replace(',', '->', substr($match, 1, -1));
			} else {
				$value = '$item["'.str_replace(',', '"]["', substr($match, 1, -1)).'"]';
			}
			$replaces[$match] = $value;
		}

		$newData = array();
		for ($i=0; $i < $columnCount; $i++) {
			foreach ($data as $k=>$item) {
				$search = array();
				$replace = array();
				foreach ($replaces as $key => $value) {
					$search[] = $key;
					eval('$r = '.$value.';');
					$replace = $r;
				}
				$liTemp = str_replace($search, $replace, $li);
				$newData[$i][] = $liTemp;
				unset($data[$k]);
				break;
			}
		}

		$return = array();
		foreach ($newData as $key => $value) {
			$return[] = '<ul>'.implode('', $value).'</ul>';
		}

		return implode('', $return);

	}

}