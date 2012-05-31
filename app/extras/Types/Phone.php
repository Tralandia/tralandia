<?php

namespace Extras\Types;

class Phone extends BaseType {

	const ORIGINAL = 'original';
	const COUNTRY = 'country';

	public function __construct($original, $country = NULL) {
		$this->data[self::ORIGINAL] = $original;
		if($country !== NULL && $country instanceof \Entity\Location\Location) {
			$coutnry = \Service\Location\Location::get($country);
			// @todo dorobit $location->isCountry()
			if(FALSE && $country->isCountry()) {
				throw new \Nette\InvalidArgumentException('Argument does not match with the expected value');
			}
		}
		$this->data[self::COUNTRY] = $country;
	}


	public static function decode($data) {
		$data = \Nette\Utils\Json::decode($data, TRUE);
		return new self($data[self::ORIGINAL], isset($data[self::COUNTRY]) ? $data[self::COUNTRY] : NULL);
	}

	public function __toString() {
		return (string)$this->data[self::ORIGINAL];
	}

}