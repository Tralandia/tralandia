<?php
namespace Tests;

use Nette, Extras;


/**
 *
 * @backupGlobals disabled
 */
class ReservationProtectorTest extends TestCase
{
	/**
	 * @var \ReservationProtector
	 */
	public $reservationProtector;

	protected function setUp() {
		$this->reservationProtector = $this->getContext()->reservationProtector;
	}

	public function testBase()
	{
		$email = 'foo@bar.com';

		$reservationProtector = $this->reservationProtector;
		$reservationProtector->canSendReservation($email);

	}
}
