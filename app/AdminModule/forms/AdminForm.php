<?php

namespace AdminModule\Forms;

class AdminForm extends Form {

	protected $environment;

	public function setEnvironment($environment) {
		$this->environment = $environment;
		return $this;
	}

	public function getEnvironment() {
		return $this->environment;
	}

	protected $defaultLanguage;

	public function setDefaultLanguage($defaultLanguage) {
		$this->defaultLanguage = $defaultLanguage;
		return $this;
	}

	public function getDefaultLanguage() {
		return $this->defaultLanguage;
	}

}
