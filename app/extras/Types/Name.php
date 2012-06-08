<?php

namespace Extras\Types;

class Name extends \Nette\Object implements IContact {

	const FIRST = 'first';
	const MIDDLE = 'middle';
	const LAST = 'last';

	public $first;
	public $middle;
	public $last;

	public function __construct($first = NULL, $middle = NULL, $last = NULL) {
		if(is_array($first)) {
			$data = $first;
			$first = array_shift($data);
			$middle = array_shift($data);
			$last = array_shift($data);
		}

		$this->first = $first;
		$this->middle = $middle;
		$this->last = $last;
	}

	public function toArray() {
		return array(
			self::FIRST => $this->first,
			self::MIDDLE => $this->middle,
			self::LAST => $this->last,
		);
	}

	public function encode() {
		return \Nette\Utils\Json::encode($this->toArray());
	}

	public function toFormValue() {
		return 'name~' . implode('~', $this->toArray());
	}

	public function __toString() {
		return implode(', ', $this->toArray());
	}

	public function getUnifiedFormat() {
		return (string) $this;
	}

	public static function decode($data) {
		$data = \Nette\Utils\Json::decode($data, TRUE);
		return new self($data);
	}

}