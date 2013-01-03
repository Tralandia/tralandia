<?php

namespace FrontModule;

class RegistrationPresenter extends BasePresenter {

	/**
	 * @autowire
	 * @var \FrontModule\Forms\IRegistrationFormFactory
	 */
	protected $registrationFormFactory;

	public function createComponentRegistrationForm()
	{
		return $this->registrationFormFactory->create($this->primaryLocation);
	}

}
