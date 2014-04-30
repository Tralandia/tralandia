<?php

namespace FrontModule;


use Nette\Utils\Html;
use Routers\BaseRoute;

class RegistrationPresenter extends BasePresenter
{


	public function actionDefault()
	{
		$this->checkPermission($this->getName(), $this->getAction());

		$jsRegistrationVariables = [];
		$jsRegistrationVariables['data-country'] = $this->primaryLocation->getId();
		$jsRegistrationVariables['data-language'] = $this->language->getId();

		$this->template->jsRegistrationVariables = Html::el('variables')->addAttributes($jsRegistrationVariables);;
		$this->template->registrationFormSubmitted = $this->request->isPost();
	}


	public function createComponentRegistrationForm(\FrontModule\RegistrationForm\IRegistrationFormFactory $factory)
	{
		$control = $factory->create();

		$control->onFormSuccess[] = function ($form, $user) {
			$identity = $this->authenticator->getIdentity($user);
			$this->login($identity);
			$this->flashMessage('o100165', RegistrationPresenter::FLASH_SUCCESS);
			$this->redirect('afterLogin');
		};


		return $control;
	}

}
