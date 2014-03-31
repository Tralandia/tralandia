<?php
namespace Listener;

use Entity\User\User;
use Nette;

class ForgotPasswordEmailListener extends BaseEmailListener
{

	public function getSubscribedEvents()
	{
		return ['BaseModule\Forms\ForgotPasswordForm::onAfterProcess'];
	}

	public function onAfterProcess(User $user)
	{
		if($user->isSuperAdmin()) return false;

		$message = new \Nette\Mail\Message();

		$emailCompiler = $this->prepareCompiler($user);

		$message->setSubject($emailCompiler->compileSubject());

		$body = $emailCompiler->compileBody();
		$message->setHtmlBody($body);

		$message->addTo($user->getLogin());
		$message->addBcc('tralandia.testing@gmail.com');

		$this->mailer->send($message);
	}


	private function prepareCompiler(User $user)
	{
		$emailCompiler = $this->createCompiler($user->getPrimaryLocation(), $user->getLanguage());
		$emailCompiler->setTemplate($this->getTemplate('v2-forgotten-password'));

		$emailCompiler->addOwner('owner', $user);

		return $emailCompiler;
	}

}
