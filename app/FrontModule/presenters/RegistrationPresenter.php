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
		$form = $this->registrationFormFactory->create($this->primaryLocation);
		$this->registrationHandler->attach($form);

		$self = $this;
		$form->onSuccess[] = function ($form) use ($self) {
			$rental = $self->registrationHandler->getRental();
			$self->rentalRepositoryAccessor->save($rental);
			if ($form->valid) $form->presenter->redirect('Rental:edit', array('id' => $rental->getId()));
		};

		return $form;
	}

}
