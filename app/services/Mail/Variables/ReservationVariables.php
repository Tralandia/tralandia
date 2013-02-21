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

}