<?php

namespace FrontModule;

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
		$this->user->logout(TRUE);
		$this->redirect('Home:default');
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

		return $comp;
	}

}
