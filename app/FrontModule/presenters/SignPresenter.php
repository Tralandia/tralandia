<?php

namespace FrontModule;

use BaseModule\Forms\SimpleForm;
use Nette\Application\BadRequestException;
use Nette\Application\UI\Form;
use Routers\BaseRoute;

class SignPresenter extends BasePresenter
{

	/**
	 * @autowire
	 * @var \BaseModule\Forms\Sign\IInFormFactory
	 */
	protected $signInFormFactory;

	/**
	 * @autowire
	 * @var \BaseModule\Forms\IForgotPasswordFormFactory
	 */
	protected $forgotPasswordFormFactory;


	public function actionIn()
	{
		if ($this->user->isLoggedIn()) {
			$this->redirect('Home:default');
		}
	}


	public function actionOut()
	{
		$nextLink = $this->getParameter('nextLink', NULL);
		$this->user->logout(TRUE);
		if($nextLink) {
			$this->redirectUrl($nextLink);
		}
		$this->redirect('Home:default');
	}


	public function actionNewPassword($np)
	{
		try {
			$this->authenticator->checkNewPasswordHash($np);
		} catch(\Nette\Security\AuthenticationException $e) {
			$this->redirect('in');
		}
	}


	/**
	 * @return \BaseModule\Forms\Sign\InForm
	 */
	protected function createComponentSignInForm()
	{
		$comp = $this->signInFormFactory->create();

		return $comp;
	}


	/**
	 * @return \BaseModule\Forms\ForgotPasswordForm
	 */
	protected function createComponentForgotPasswordForm()
	{
		$comp = $this->forgotPasswordFormFactory->create();
		$comp->onSuccess[] = function ($form) {
			$form->presenter->flashMessage(['152281'], SignPresenter::FLASH_SUCCESS);
			$form->presenter->redirect('this');
		};


		return $comp;
	}

	/**
	 * @return \BaseModule\Forms\Sign\InForm
	 */
	protected function createComponentNewPasswordForm()
	{
		$form = $this->simpleFormFactory->create();

		$form->addPassword('password', '721626')
			->setOption('help', $this->translate('o100145'))
			->setOption('prepend', '<i class="icon-lock"></i>')
			->setRequired($this->translate('o100145'))
			->addRule($form::MIN_LENGTH, $this->translate('1328'), 5);

		$form->addPassword('password2', '721627')
			->setOption('prepend', '<i class="icon-lock"></i>')
			->setRequired($this->translate('o100145'));


		$form->addSubmit('submit', '1318');

		$form->onValidate[] = function(SimpleForm $form) {
			$values = $form->getValues();
			if($values->password != $values->password2) {
				$form['password2']->addError($this->translate('558'));
			}
		};

		$form->onSuccess[] = $this->newPasswordFormSuccess;

		return $form;
	}


	public function newPasswordFormSuccess(SimpleForm $form)
	{
		$values = $form->getValues();

		$user = $this->authenticator->getUserFromHash($this->getParameter(BaseRoute::NEW_PASSWORD, 0));
		if(!$user) {
			throw new BadRequestException();
		}

		$user->setPassword($this->authenticator->calculatePasswordHash($values->password));

		$this->userDao->save($user);


		$identity = $this->authenticator->getIdentity($user);

		$this->flashMessage($this->translate(700), self::FLASH_SUCCESS);
		$this->login($identity);
		$this->redirect('afterLogin');
	}

}
