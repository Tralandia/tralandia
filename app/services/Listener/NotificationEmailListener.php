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
		$emailCompiler = $this->prepareCompiler($rental);

		$email = $rental->getContactEmail();

		$this->send($emailCompiler, $email);
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
