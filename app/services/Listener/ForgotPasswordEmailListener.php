<?php
namespace Listener;

use Environment\Environment;
use Nette;

class ForgotPasswordEmailListener extends BaseEmailListener
{

	public function getSubscribedEvents()
	{
		return ['BaseModule\Forms\ForgotPasswordForm::onAfterProcess'];
	}

	public function onAfterProcess(\Entity\User\User $user)
	{
		if($user->isSuperAdmin()) return false;

		$message = new \Nette\Mail\Message();

		$emailCompiler = $this->prepareCompiler($user);

		$message->setSubject($emailCompiler->compileSubject());

		$body = $emailCompiler->compileBody();
		$message->setHtmlBody($body);

		$message->addTo($user->getLogin());

		$this->mailer->send($message);
	}


	private function prepareCompiler(\Entity\User\User $user)
	{
		$emailCompiler = $this->createCompiler($user->getPrimaryLocation(), $user->getLanguage());
		$emailCompiler->setTemplate($this->getTemplate('forgotten-password'));

		$emailCompiler->addOwner('owner', $user);

		return $emailCompiler;
	}

}
