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

		$message->setHtmlBody($body);
		$message->addTo($rental->getOwner()->getLogin());

		$this->mailer->send($message);
	}

	/**
	 * @param \Entity\Rental\Rental $rental
	 *
	 * @return \Extras\Email\Compiler
	 */
	private function prepareCompiler(\Entity\Rental\Rental $rental)
	{
		$receiver = $rental->getOwner();

		$emailCompiler = $this->emailCompiler;
		$emailCompiler->setTemplate($this->getTemplate(7));
		$emailCompiler->setLayout($this->getLayout());
		$emailCompiler->setEnvironment($receiver->getPrimaryLocation(), $receiver->getLanguage());
		$emailCompiler->addRental('rental', $rental);
		$emailCompiler->addOwner('owner', $receiver);

		return $emailCompiler;
	}

}