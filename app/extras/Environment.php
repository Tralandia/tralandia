<?php

namespace Extras;

class Environment extends \Nette\Object {

	private $location = NULL;
	private $country = NULL;
	private $language = NULL;

	public static function factory() {
		return new static();
	}

	public function __construct() {
	}

	public function getLocation() {
		if($this->location === NULL) {
			$this->location = $this->loadLocation();
		}
		return $this->location;
	}
	
	protected function loadLocation() {
		return \Service\Location\Location::get(46);
	}


	public function getCountry() {
		if($this->country === NULL) {
			$this->country = $this->getLocation()->country;
			if(!$this->country) $this->country = FALSE;
		}
		return $this->country;
	}

	public function getLanguage() {
		if($this->language === NULL) {
			$this->language = $this->loadLanguage();
		}
		return $this->language;
	}
	
	protected function loadLanguage() {
		return \Service\Dictionary\Language::get(144);
	}
	
}