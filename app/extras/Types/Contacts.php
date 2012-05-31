<?php

namespace Extras\Types;

class Contacts extends BaseType {

	public static function decode($data) {
		$data = \Nette\Utils\Json::decode($data, TRUE);
		return new self();
	}

	public function __toString() {
		return '';
	}

}