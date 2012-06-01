<?php

namespace Extras\Types;

class Address extends \Nette\Object implements IContact {

	const ADDRESS = 'address';
	const ADDRESS2 = 'address2';
	const ZIPCODE = 'zipcode';
	const COUNTRY = 'country';

	public $address;
	public $address2;
	public $zipcode;
	public $country;


	/**
	 * @param string|array $address
	 * @param string $address2
	 * @param string $zipcode
	 * @param string $country
	 */
	public function __construct($address = NULL, $address2 = NULL, $zipcode = NULL, $country = NULL) {
		if(is_array($address)) {
			$data = $address;
			$address = array_shift($data);
			$address2 = array_shift($data);
			$zipcode = array_shift($data);
			$country = array_shift($data);
		}

		$this->address = $address;
		$this->address2 = $address2;
		$this->zipcode = $zipcode;
		$this->country = $country;
	}

	public function _toArray() {
		return array(
			self::ADDRESS => $this->address,
			self::ADDRESS2 => $this->address2,
			self::ZIPCODE => $this->zipcode,
			self::COUNTRY => $this->country,
		);
	}

	public function encode() {
		return \Nette\Utils\Json::encode((array) $this);
	}

	public static function decode($data) {
		$data = \Nette\Utils\Json::decode($data, TRUE);
		return new self($data);
	}

}