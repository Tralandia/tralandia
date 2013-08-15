<?php
namespace Listener;

use Entity\User\RentalReservation;
use Nette;

class ReservationSenderEmailListener extends BaseEmailListener
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

		$message->setFrom($reservation->getRental()->getEmail());
		$message->addTo($reservation->getSenderEmail(), $reservation->getSenderName());

		$this->mailer->send($message);
	}

	/**
	 * @param \Entity\User\RentalReservation $reservation
	 *
	 * @return \Mail\Compiler
	 */
	public function prepareCompiler(RentalReservation $reservation)
	{
		$primaryLocation = $reservation->getRental()->getAddress()->getPrimaryLocation();

		$emailCompiler = $this->createCompiler($primaryLocation, $reservation->getLanguage());
		$emailCompiler->setTemplate($this->getTemplate('reservation-client'));
		$emailCompiler->addRental('rental', $reservation->getRental());
		$emailCompiler->addReservation('reservation', $reservation);

		return $emailCompiler;
	}

}
