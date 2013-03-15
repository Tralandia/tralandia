<?php
namespace Listener;

use Entity\User\RentalReservation;
use Nette;

class ReservationOwnerEmailListener extends BaseEmailListener implements \Kdyby\Events\Subscriber
{

	public function getSubscribedEvents()
	{
		return ['FormHandler\RegistrationHandler::onSuccess'];
	}

	public function onSuccess(RentalReservation $reservation)
	{
		$message = new \Nette\Mail\Message();

		$emailCompiler = $this->prepareCompiler($reservation);
		$body = $emailCompiler->compileBody();

		$message->setHtmlBody($body);
		$message->addTo($reservation->getRental()->getOwner()->getLogin());

		$this->mailer->send($message);
	}

	/**
	 * @param \Entity\User\RentalReservation $reservation
	 *
	 * @return \Mail\Compiler
	 */
	public function prepareCompiler(RentalReservation $reservation)
	{
		$receiver = $reservation->getRental()->getOwner();

		$emailCompiler = $this->getCompiler($receiver->getPrimaryLocation(), $receiver->getLanguage());
		$emailCompiler->setTemplate($this->getTemplate('reservation-form'));
		$emailCompiler->setLayout($this->getLayout());
		$emailCompiler->addRental('rental', $reservation->getRental());
		$emailCompiler->addReservation('reservation', $reservation);

		return $emailCompiler;
	}

}