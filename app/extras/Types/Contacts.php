<?php

namespace Extras\Types;

class Contacts extends \Nette\Object {

	private $contacts;

	public function add(IContact $contact) {
		$this->contacts[] = $contact;
	}


	public function encode() {
		$contacts = array();
		if (is_array($this->contacts)) {
			foreach ($this->contacts as $contact) {
				$contactClassName = get_class($contact);
				$contacts[] = array(
					'className' => $contactClassName,
					'data' => $contact->encode(),
				);
			}
			return \Nette\Utils\Json::encode($contacts);			
		} else {
			return NULL;
		}
	}

	public static function decode($data) {
		$data = \Nette\Utils\Json::decode($data, TRUE);
		$contacts = new self();
		if (is_array($contacts)) {
			foreach ($data as $contact) {
				$className = $contact['className'];
				$contacts->add(new $className($contact['data']));
			}
		}
		return $contacts;
	}

	public function __toString() {
		return '';
	}

}