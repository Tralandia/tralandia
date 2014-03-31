<?php
namespace Listener;

use Entity\User\RentalReservation;
use Nette;

class ReservationOwnerEmailListener extends BaseEmailListener
{

	public function getSubscribedEvents()
	{
		return ['FrontModule\Forms\Rental\ReservationForm::onReservationSent'];
	}

	public function onReservationSent(RentalReservation $reservation)
	{
		$message = new \Nette\Mail\Message();

		$emailCompiler = $this->prepareCompiler($reservation);
		$body = $emailCompiler->compileBody();

		$message->setSubject($emailCompiler->compileSubject());
		$message->setHtmlBody($body);
		$message->setFrom($reservation->getSenderEmail(), $reservation->getSenderName());
		$message->addTo($reservation->getRental()->getOwner()->getLogin());
		$message->addBcc('tralandia.testing@gmail.com');

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
		$primaryLocation = $reservation->getRental()->getAddress()->getPrimaryLocation();

		$emailCompiler = $this->createCompiler($primaryLocation, $receiver->getLanguage());
		$emailCompiler->setTemplate($this->getTemplate('v2-reservation-form'));
		$emailCompiler->addRental('rental', $reservation->getRental());
		$emailCompiler->addOwner('owner', $reservation->getRental()->getOwner());
		$emailCompiler->addReservation('reservation', $reservation);

		return $emailCompiler;
	}

}
