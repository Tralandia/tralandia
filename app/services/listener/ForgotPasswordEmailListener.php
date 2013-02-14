<?php
namespace Listener;

use Nette;

class ForgotPasswordEmailListener extends BaseEmailListener implements \Kdyby\Events\Subscriber
{

	public function getSubscribedEvents()
	{
		return ['BaseModule\Forms\ForgotPasswordForm::onAfterProcess'];
	}

	public function onAfterProcess(\Entity\User\User $user)
	{
		d('Akoze som poslal email o zabudnutom hesle');
	}
}