<?php
namespace Listener;

use Nette;

class NotificationEmailListener extends BaseEmailListener implements \Kdyby\Events\Subscriber
{

	public function getSubscribedEvents()
	{
//		return ['FormHandler\RegistrationHandler::onSuccess'];
		return [];
	}


	public function onSuccess(\Entity\Rental\Rental $rental)
	{
		$message = new \Nette\Mail\Message();

		$emailCompiler = $this->prepareCompiler($rental);
		$body = $emailCompiler->compileBody();

		$message->setSubject($emailCompiler->compileSubject());
		$message->setHtmlBody($body);

		$email = $rental->getContactEmail();
		$message->addTo($email);

		$this->mailer->send($message);
	}


	/**
	 * @param \Entity\Rental\Rental $rental
	 *
	 * @return \Mail\Compiler
	 */
	private function prepareCompiler(\Entity\Rental\Rental $rental)
	{
		$receiver = $rental->getOwner();

		$emailCompiler = $this->createCompiler($receiver->getPrimaryLocation(), $receiver->getLanguage());
		$emailCompiler->setTemplate($this->getTemplate('v2-notification-email'));
		$emailCompiler->addRental('rental', $rental);
		$emailCompiler->addOwner('owner', $receiver);

		return $emailCompiler;
	}

}
