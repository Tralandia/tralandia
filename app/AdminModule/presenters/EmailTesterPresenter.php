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
			$this->redirect('this');
		};
		return $control;
	}

}
