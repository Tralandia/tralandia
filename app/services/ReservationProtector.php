<?php


class ReservationProtector {

	const MAX_COUNT = 50;

	/**
	 * @var Nette\Http\SessionSection
	 */
	protected $section;


	/**
	 * @param \Nette\Http\Session $session
	 */
	public function __construct(\Nette\Http\Session $session)
	{
		$this->section = $session->getSection('reservationProtector');
	}


	/**
	 * @param $email
	 */
	protected function newReservationSent($email)
	{
		$this->setLastReservationTime(new \DateTime);
		$this->increaseSentReservationCountInRow();
		$this->increaseSentReservationCountForEmail($email);
	}


	/**
	 * @param $email
	 *
	 * @throws InfringeMinIntervalReservationException
	 * @throws TooManyReservationInRowException
	 * @throws TooManyReservationForEmailException
	 */
	public function canSendReservation($email)
	{
		if($this->getSentReservationCountInRow() >= self::MAX_COUNT) {
			throw new TooManyReservationInRowException;
		}

		$lastReservationTime = $this->getLastReservationTime();
		$fiveSecondAgo = (new DateTime)->modify('-5 sec');
		if($lastReservationTime && $lastReservationTime > $fiveSecondAgo) {
			throw new InfringeMinIntervalReservationException;
		}

		if($this->getSentReservationCountForEmail($email) >= self::MAX_COUNT) {
			throw new TooManyReservationForEmailException;
		}
	}


	/**
	 * @return int
	 */
	public function getSentReservationCountInRow()
	{
		return (int) $this->section->sentReservationCountInRow;
	}


	public function increaseSentReservationCountInRow()
	{
		$this->section->sentReservationCountInRow++;
	}


	/**
	 * @param null $email
	 *
	 * @return mixed
	 */
	public function getSentReservationCountForEmail($email = NULL)
	{
		if(!$email) {
			return $this->section->sentReservationCountByEmail;
		} else {
			return $this->section->sentReservationCountByEmail[$email];
		}
	}


	/**
	 * @param $email
	 */
	public function increaseSentReservationCountForEmail($email)
	{
		$this->section->sentReservationCountByEmail[$email]++;
	}


	/**
	 * @param \DateTime $date
	 */
	public function setLastReservationTime(\DateTime $date = NULL)
	{
		$this->section->lastReservationTime = $date;
	}


	/**
	 * @return \DateTime|NULL
	 */
	public function getLastReservationTime()
	{
		return $this->section->lastReservationTime;
	}
}

class TooManyReservationInRowException extends \Exception { }
class TooManyReservationForEmailException extends \Exception { }
class InfringeMinIntervalReservationException extends \Exception { }
