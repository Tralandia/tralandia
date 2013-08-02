<?php
namespace Listener;

use Nette;

class RegistrationEmailListener extends BaseEmailListener implements \Kdyby\Events\Subscriber
{

	public function getSubscribedEvents()
	{
		return ['FormHandler\RegistrationHandler::onSuccess'];
	}


	public function onSuccess(\Entity\Rental\Rental $rental)
	{
		$message = new \Nette\Mail\Message();

		$emailCompiler = $this->prepareCompiler($rental);
		$body = $emailCompiler->compileBody();

		$message->setSubject($emailCompiler->compileSubject());
		$message->setHtmlBody($body);

		$message->addTo($rental->getOwner()->getLogin());

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

		$emailCompiler = $this->getCompiler($receiver->getPrimaryLocation(), $receiver->getLanguage());
		$emailCompiler->setTemplate($this->getTemplate('registration-email'));
		$emailCompiler->setLayout($this->getLayout());
		$emailCompiler->addRental('rental', $rental);
		$emailCompiler->addOwner('owner', $receiver);

		return $emailCompiler;
	}

}
