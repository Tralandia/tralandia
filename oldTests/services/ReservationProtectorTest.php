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

	/**
	 * @var int
	 */
	public $maxCount = 5;


	protected function setUp() {
		$this->reservationProtector = $this->getContext()->reservationProtector;
		$this->reservationProtector->setMaxCount($this->maxCount);
		$this->reservationProtector->setMinInterval(0);
	}

	public function testBase()
	{
		$email = 'foo@bar.com';
		$remoteAddress = '120.4.0.1';
		$reservationProtector = $this->reservationProtector;
		$reservationProtector->reset();

		$this->assertEquals(TRUE, $reservationProtector->canSendReservation($email,$remoteAddress));
	}


	public function testThrowTooManyForEmail()
	{
		$email = 'foo@bar.com';
		$email2 = 'baz@bar.com';
		$reservationProtector = $this->reservationProtector;
		$reservationProtector->reset();

		for( $i = 1; $i < $this->maxCount; $i++ ) {
			$reservationProtector->newReservationSent($email);
		}

		$this->assertEquals(TRUE, $reservationProtector->canSendReservation($email));

		$reservationProtector->newReservationSent($email);

		$this->assertEquals(TRUE, $reservationProtector->canSendReservation($email2));

		try {
			$reservationProtector->canSendReservation($email);
		} catch (\TooManyReservationForEmailException $e) {
			$e->getCode();
			return;
		}

		$this->fail('An expected exception has not been raised.');
	}


	public function testTimeInterval()
	{
		$email = 'foo@bar.com';
		$reservationProtector = $this->reservationProtector;
		$reservationProtector->reset();
		$reservationProtector->setMinInterval(1);

		$reservationProtector->newReservationSent($email);

		sleep(1);
		$this->assertEquals(TRUE, $reservationProtector->canSendReservation($email));

		$reservationProtector->newReservationSent($email);

		try {
			$reservationProtector->canSendReservation($email);
		} catch (\InfringeMinIntervalReservationException $e) {
			$e->getCode();
			return;
		}
		$this->fail('An expected exception has not been raised.');

	}

	public function testBlackList()
	{
		$reservationProtector = $this->reservationProtector;
		$reservationProtector->reset();

		/** @var $blackListEntity \Entity\User\BlackList */
		$blackListEntity = $this->getEm()->getRepository(BLACK_LIST_ENTITY)->find(1);

		try {
			$reservationProtector->canSendReservation($blackListEntity->getEmail());
		} catch (\EmailIsOnBlackListException $e) {
			$e->getCode();
			return;
		}
		$this->fail('An expected exception has not been raised.');

	}

}
