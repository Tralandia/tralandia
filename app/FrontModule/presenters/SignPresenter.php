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
		$nextLink = $this->getParameter('nextLink', NULL);
		$this->user->logout(TRUE);
		if($nextLink) {
			$this->redirectUrl($nextLink);
		}
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
		$comp->onSuccess[] = function ($form) {
			$form->presenter->flashMessage(['152281'], SignPresenter::FLASH_SUCCESS);
			$form->presenter->redirect('this');
		};


		return $comp;
	}

}
