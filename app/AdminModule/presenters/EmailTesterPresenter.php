<?php

namespace AdminModule;


use Entity\User\Role;
use Nette\Application\BadRequestException;
use Nette\Application\UI\Form;

class EmailTesterPresenter extends BasePresenter
{


	protected function createComponentEmailTesterForm(\AdminModule\EmailTester\EmailTesterForm $control)
	{
		$control->onFormSuccess[] = function () {
			$this->flashMessage('email sent!');
			$this->redirect('this');
		};
		return $control;
	}

	protected function createComponentNewsletterTesterForm(\AdminModule\EmailTester\NewsletterTesterForm $control)
	{
		$control->onFormSuccess[] = function () {
			$this->flashMessage('email sent!');
			$this->redirect('this');
		};
		return $control;
	}



}
