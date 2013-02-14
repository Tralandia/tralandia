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

		$emailCompiler = $this->emailCompiler;
		$emailCompiler->setTemplate($this->getTemplate(7));
		$emailCompiler->setLayout($this->getLayout());
		$emailCompiler->setEnvironment($receiver->getPrimaryLocation(), $receiver->getLanguage());
		$emailCompiler->addRental('rental', $rental);
		$emailCompiler->addVisitor('sender', $sender);
		$emailCompiler->addCustomVariable('message', 'Toto je sprava pre teba!');
		$html = $emailCompiler->compile();

	}

}