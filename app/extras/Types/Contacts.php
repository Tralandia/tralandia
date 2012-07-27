<?php

namespace Extras\Types;

class Contacts extends \Nette\Object {

	private $list;

	public function getList() {
		return $this->list;
	}

	public function add(IContact $contact) {
		$this->list[] = $contact;
	}

	public function addFromString($contacts) {
		$contacts = explode("\n", $contacts);
		foreach ($contacts as $contact) {
			$contact = explode('~', trim($contact));
			$className = '\Extras\Types\\'.ucfirst(array_shift($contact));
			$contact = count($contact) > 1 ? $contact : array_shift($contact);
			if($className == '\Extras\Types\Phone') $contact = implode('', $contact);
			$contact = new $className($contact);
			$this->add($contact);
		}
		return $this;
	}

	public function getByType($type = NULL, $limit = 15) {

		if ($type!=NULL && !is_array($type)) {
			$type = array($type);
		}

		$contacts = array();
		foreach ($this->list as $contact) {
			
			$class = explode('\\', get_class($contact));
			$class = strtolower(end($class));

			if (!isset($contacts[$class])) $contacts[$class] = array();

			if (
					(is_array($type) && in_array($class, $type))
					|| ($type && $class==$type)
					|| (!$type)
				)
			{
				$contacts[$class][] = $contact;
			}

		}

		return \Nette\ArrayHash::from($contacts);

	}


	public function encode() {
		$contacts = array();
		if (is_array($this->list)) {
			foreach ($this->list as $contact) {
				$contactClassName = get_class($contact);
				$contacts[] = array(
					'className' => $contactClassName,
					'data' => $contact->toArray(),
				);
			}
			return \Nette\Utils\Json::encode($contacts);			
		} else {
			return NULL;
		}
	}

	public static function decode($data) {
		$data = \Nette\Utils\Json::decode($data, TRUE);
		$contacts = new self;
		if (is_array($data)) {
			foreach ($data as $contact) {
				$className = $contact['className'];
				$contacts->add(new $className($contact['data']));
			}
		}
		return $contacts;
	}


	public function __toString() {
		if(!is_array($this->list)) return '';
		$return = array();
		foreach ($this->list as $contact) {
			$return[] = $contact->toFormValue();
		}
		return implode("\n", $return);
	}

}