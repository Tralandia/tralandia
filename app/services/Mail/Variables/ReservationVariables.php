<?php
namespace Mail\Variables;

use Entity\User\RentalReservation;
use Nette;

/**
 * ReservationVariables class
 *
 * @author Dávid Ďurika
 */
class ReservationVariables extends Nette\Object {

	/**
	 * @var \Entity\User\RentalReservation
	 */
	private $reservation;

	public function __construct(RentalReservation $reservation) {
		$this->reservation = $reservation;
	}

	public function getVariableDetails()
	{
		return '#DETAIL#';
	}

	public function getVariableSenderName() {
		return $this->reservation->getSenderName();
	}

	public function getVariableSenderEmail() {
		return $this->reservation->getSenderEmail();
	}

	public function getVariableSenderPhone() {
		$phone = $this->reservation->getSenderPhone();
		return $phone ? $phone->getInternational() : NULL;
	}

	public function getVariableArrivalDate() {
		return $this->reservation->getArrivalDate();
	}

	public function getVariableDepartureDate() {
		return $this->reservation->getDepartureDate();
	}

	public function getVariableAdultsCount() {
		return $this->reservation->getAdultsCount();
	}

	public function getVariableChildrenCount() {
		return $this->reservation->getChildrenCount();
	}

	public function getVariableMessage()
	{
		return $this->reservation->getMessage();
	}
}
