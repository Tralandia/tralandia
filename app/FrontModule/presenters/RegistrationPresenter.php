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


	public function actionDefault()
	{
		$this->checkPermission($this->getName(), $this->getAction());
		$this->template->registrationFormSubmitted = $this->request->isPost();
	}


	public function createComponentRegistrationForm()
	{
		$form = $this->registrationFormFactory->create($this->environment, $this);
		$this->registrationHandler->attach($form);

		$self = $this;
		$form->onSuccess[] = function ($form) use ($self) {
			$rental = $self->registrationHandler->getRental();
			$self->onSuccessRegistration($rental);

			$self->flashMessage('o100193', RegistrationPresenter::FLASH_ERROR);
			$self->redirect('Home:default');

//			$owner = $rental->getOwner();
//			$self->login($owner);
//			$self->flashMessage('o100165', RegistrationPresenter::FLASH_SUCCESS);
//			$self->redirect(':Owner:Rental:edit', array('id' => $rental->getId()));
		};


		return $form;
	}

}
