<?php

namespace Extras\Types;

class Address extends \Nette\Object implements IContact {

	const ADDRESS = 'address';
	const ADDRESS2 = 'address2';
	const LOCALITY = 'locality';
	const POSTCODE = 'postcode';
	const COUNTRY = 'country';

	public $address;
	public $address2;
	public $locality;
	public $postcode;
	public $country;

	/**
	 * @param string|array $address
	 * @param string $address2
	 * @param string $locality
	 * @param string $postcode
	 * @param string $country
	 */
	public function __construct($address = NULL, $address2 = NULL, $locality = NULL, $postcode = NULL, $country = NULL) {
		if(is_array($address)) {
			$data = $address;
			$address = array_shift($data);
			$address2 = array_shift($data);
			$locality = array_shift($data);
			$postcode = array_shift($data);
			$country = array_shift($data);
		}

		$this->address = $address;
		$this->address2 = $address2;
		$this->locality = $locality;
		$this->postcode = $postcode;
		$this->country = $country;
	}

	public function toArray() {
		return array(
			self::ADDRESS => $this->address,
			self::ADDRESS2 => $this->address2,
			self::LOCALITY => $this->locality,
			self::POSTCODE => $this->postcode,
			self::COUNTRY => $this->country,
		);
	}

	public function encode() {
		return \Nette\Utils\Json::encode($this->toArray());
	}

	public function __toString() {
		$t = array($this->address, $this->address2, $this->locality, $this->postcode, $this->country);
		$t = implode(', ', array_filter($t));
		return $t;
	}

	public function getUnifiedFormat() {
		return $this->__toString();
	}

	public static function decode($data) {
		$data = \Nette\Utils\Json::decode($data, TRUE);
		return new self($data);
	}

}