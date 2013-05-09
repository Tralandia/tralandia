<?php


trait TToArray {

	/**
	 * @param $source
	 * @param $keyKey
	 * @param $keyValue
	 *
	 * @return array
	 */
	public function getArrayForSelect($source, $keyKey, $keyValue)
	{
		if(!is_scalar($keyKey)) {
			$keyCallback = new \Nette\Callback($keyKey);
		}

		if(!is_scalar($keyValue)) {
			$valueCallback = new \Nette\Callback($keyValue);
		}

		$return = [];
		foreach($source as $key => $value) {
			if(isset($keyCallback)) {
				$newKey = $keyCallback->invokeArgs($key, $value);
			} else {
				$newKey = $value[$keyKey];
			}

			if(isset($valueCallback)) {
				$newValue = $valueCallback->invokeArgs($value);
			} else {
				$newValue = $value[$keyValue];
			}

			$return[$newKey] = $newValue;
		}

		return $return;
	}

}
