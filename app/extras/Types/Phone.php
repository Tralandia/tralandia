<?php

namespace Extras\Types;

class Phone extends \Nette\Object implements IContact {

	const ORIGINAL = 'original';
	const COUNTRY = 'country';

	public $original;
	public $country;

	public function __construct($original, $country = NULL) {
		if(is_array($original)) {
			$data = $original;
			$original = array_shift($data);
			$country = array_shift($data);
		}
		$this->original = $original;
		if($country !== NULL && $country instanceof \Entity\Location\Location) {
			$coutnry = \Service\Location\Location::get($country);
			// @todo dorobit $location->isCountry()
			if(FALSE && $country->isCountry()) {
				throw new \Nette\InvalidArgumentException('Argument does not match with the expected value');
			}
		}
		$this->country = $country;
	}

	public function toArray() {
		return array(
			self::ORIGINAL => $this->original,
			self::COUNTRY => $this->country,
		);
	}

	public function encode() {
		return \Nette\Utils\Json::encode($this->toArray());
	}


	public static function decode($data) {
		$data = \Nette\Utils\Json::decode($data, TRUE);
		return new self($data[self::ORIGINAL], isset($data[self::COUNTRY]) ? $data[self::COUNTRY] : NULL);
	}

	public function toFormValue() {
		$s = (string) $this;
		return 'phone~' . substr($s, 0, 3) . '~' . substr($s, 3);
	}

	public function __toString() {
		return (string) $this->original;
	}

	public function getUnifiedFormat() {
		return (string) $this;
	}

}