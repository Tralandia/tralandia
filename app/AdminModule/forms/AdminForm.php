<?php

namespace AdminModule\Forms;

class AdminForm extends Form {

	protected $environment;

	protected $defaultLanguage;

	
	public function setEnvironment($environment) {
		$this->environment = $environment;
		return $this;
	}

	public function getEnvironment() {
		return $this->environment;
	}


	public function setDefaultLanguage($defaultLanguage) {
		$this->defaultLanguage = $defaultLanguage;
		return $this;
	}

	public function getDefaultLanguage() {
		return $this->defaultLanguage;
	}

}
