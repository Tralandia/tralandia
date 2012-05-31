<?php

namespace Extras\Types;

class Contacts extends \Nette\Object {

	private $contacts;

	public function add(IContact $contact) {
		$this->contacts[] = $contact;
	}


	public function encode() {
		$contacts = array();
		foreach ($this->contacts as $contact) {
			$contactClassName = get_class($contact);
			$contacts[] = array(
				'className' => $contactClassName,
				'data' => $contact->encode(),
			);
		}
		return \Nette\Utils\Json::encode($contacts);
	}

	public static function decode($data) {
		$data = \Nette\Utils\Json::decode($data, TRUE);
		$contacts = new self();
		foreach ($data as $contact) {
			$contacts->add(new {$contact['className']}($contact['data']));
		}
		return new self($data);
	}

	public function __toString() {
		return '';
	}

}