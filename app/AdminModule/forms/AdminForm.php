<?php

namespace AdminModule\Forms;

class AdminForm extends Form {

	protected $environment;

	protected $defaultLanguage;

	protected $user;
	
	public function setEnvironment($environment) {
		$this->environment = $environment;
		return $this;
	}

	protected function buildForm() {

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

	public function setUser($user) {
		$this->user = $user;
		return $this;
	}

	public function getUser() {
		return $this->user;
	}

}
