<?php

namespace FrontModule;


class RegistrationPresenter extends BasePresenter
{

	/**
	 * @var array
	 */
	public $onSuccessRegistration = [];

	/**
	 * @autowire
	 * @var \FormHandler\RegistrationHandler
	 */
	public $registrationHandler;

	/**
	 * @autowire
	 * @var \FrontModule\Forms\IRegistrationFormFactory
	 */
	protected $registrationFormFactory;


	public function createComponentRegistrationForm()
	{
		$form = $this->registrationFormFactory->create($this->environment, $this);
		$this->registrationHandler->attach($form);

		$self = $this;
		$form->onSuccess[] = function ($form) use ($self) {
			$rental = $self->registrationHandler->getRental();
			$self->onSuccessRegistration($rental);
			$owner = $rental->getOwner();
			$self->login($owner);
			$this->presenter->flashMessage('o100165', RegistrationPresenter::FLASH_SUCCESS);
			$form->presenter->redirect(':Owner:Rental:edit', array('id' => $rental->getId()));
		};


		return $form;
	}

}
