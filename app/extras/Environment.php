<?php

namespace Extras;

class Environment extends \Nette\Object {

	private $location = NULL;
	private $language = NULL;

	// @todo
	private $url = NULL; // Extras\Type\Url
	private $currency = NULL; // 
	private $locale = NULL;

	public function __construct() {
		//debug('Environment::__construct');
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

	public function getLanguage() {
		if($this->language === NULL) {
			$this->language = $this->loadLanguage();
		}
		return $this->language;
	}
	
	protected function loadLanguage() {
		//return \Service\Dictionary\Language::get(144);
	}
	
}