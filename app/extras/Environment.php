<?php

namespace Extras;

class Environment extends \Nette\Object {

	private $location = NULL;
	private $language = NULL;

	// @todo
	private $url = NULL; // Extras\Type\Url
	private $currency = NULL; // 
	private $locale = NULL;

	public $languageRepository;
	public $locationRepository;

	public function __construct($languageRepository, $locationRepository) {
		$this->locationRepository = $locationRepository;
		$this->languageRepository = $languageRepository;
	}

	public function getLocation() {
		return $this->locationRepository->find(56);
	}

	public function getLanguage() {
		return $this->languageRepository->find(144);
	}
	
}