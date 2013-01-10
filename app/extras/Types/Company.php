<?php

namespace Extras\Types;

class Company extends BaseType {

	public $name;
	public $address;
	public $companyId;
	public $companyVatId;
	public $vat;
	public $registrator;

	public function __construct() {
	}

	static function setFromJson($data) {
		$data = \Nette\Utils\Json::decode($data);
		$company = new self;
		foreach ($data as $key => $value) {
			if (isset($company->$key)) {
				$company->$key = $value;
			}
		}
		return $company;
	}

	public function getAsJson() {
		$t = get_object_vars($this);
		$data = array();
		foreach ($t as $key => $value) {
			if ($this->$value !== NULL) {
				$data[$value] = $this->$value;
			}
		}

		return \Nette\Utils\Json::encode($data);
	}
}