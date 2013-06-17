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
