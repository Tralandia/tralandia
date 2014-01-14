<?php

namespace FrontModule;


use Nette\Utils\Html;
use Routers\BaseRoute;

class RegistrationPresenter extends BasePresenter
{

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

		$jsRegistrationVariables = [];
		$jsRegistrationVariables['data-country'] = $this->primaryLocation->getId();
		$jsRegistrationVariables['data-language'] = $this->language->getId();

		$this->template->jsRegistrationVariables = Html::el('variables')->addAttributes($jsRegistrationVariables);;
		$this->template->registrationFormSubmitted = $this->request->isPost();
	}


	public function createComponentRegistrationForm()
	{
		$form = $this->registrationFormFactory->create($this->environment, $this);

		$linkSource = $this->getParameter(BaseRoute::LINK_SOURCE, NULL);
		$args = [];
		$linkSource && $args[BaseRoute::LINK_SOURCE] = $linkSource;

		$form->setAction($this->link('this', $args));
		$this->registrationHandler->attach($form);

		$self = $this;
		$form->onSuccess[] = function ($form) use ($self, $linkSource) {
			$rental = $self->registrationHandler->getRental();

			$rental->setRegisteredFromEmail($linkSource);

			$self->rentalDao->save($rental);

//			$self->flashMessage('o100193', RegistrationPresenter::FLASH_ERROR);
//			$self->redirect('Home:default');

			$owner = $rental->getOwner();
			$identity = $this->authenticator->getIdentity($owner);
			$self->login($identity);
			$self->flashMessage('o100165', RegistrationPresenter::FLASH_SUCCESS);
			$self->redirect(':Owner:Rental:firstRental');
		};


		return $form;
	}

}
