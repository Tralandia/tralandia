<?php

namespace FrontModule;

class RegistrationPresenter extends BasePresenter {

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
		$form = $this->registrationFormFactory->create($this->primaryLocation, $this);
		$this->registrationHandler->attach($form);

		$self = $this;
		$form->onSuccess[] = function ($form) use ($self) {
			$rental = $self->registrationHandler->getRental();
			$self->rentalRepositoryAccessor->get()->save($rental);
			$owner = $rental->getOwner();
			$self->login($owner);
			$form->presenter->redirect(':Owner:Rental:edit', array('id' => $rental->getId()));
		};

		return $form;
	}

}
