<?php

namespace Extras;

class Environment extends \Nette\Object {

	private $location = NULL;
	private $language = NULL;

	// @todo
	private $url = NULL; // Extras\Type\Url
	private $currency = NULL; // 
	private $locale = NULL;

	public $languageRepositoryAccessor;
	public $locationRepositoryAccessor;

	public function __construct($languageRepositoryAccessor, $locationRepositoryAccessor) {
		$this->languageRepositoryAccessor = $languageRepositoryAccessor;
		$this->locationRepositoryAccessor = $locationRepositoryAccessor;
	}

	public function getLocation() {
		return $this->locationRepositoryAccessor->get()->find(56);
	}

	public function getLanguage() {
		return $this->languageRepositoryAccessor->get()->find(144);
	}
	
}