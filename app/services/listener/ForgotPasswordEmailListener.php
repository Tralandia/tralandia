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
		$message = new \Nette\Mail\Message();

		$emailCompiler = $this->prepareCompiler($user);
		$body = $emailCompiler->compileBody();

		$message->setHtmlBody($body);
		$message->addTo($user->getLogin());

		$this->mailer->send($message);
	}


	private function prepareCompiler(\Entity\User\User $user)
	{

		$emailCompiler = $this->emailCompiler;
		$emailCompiler->setTemplate($this->getTemplate('forgotten-password'));
		$emailCompiler->setLayout($this->getLayout());
		$emailCompiler->setEnvironment($user->getPrimaryLocation(), $user->getLanguage());

		return $emailCompiler;
	}

}