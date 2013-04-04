<?php


class ReservationProtector {

	/**
	 * @var int
	 */
	protected $maxCount = 50;

	/**
	 * @var int
	 */
	protected $minInterval = 5;

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
	public function newReservationSent($email)
	{
		$this->setLastReservationTime(new \DateTime);
		$this->increaseSentReservationCountInRow();
		$this->increaseSentReservationCountForEmail($email);
	}


	/**
	 * @param $email
	 *
	 * @return bool
	 * @throws InfringeMinIntervalReservationException
	 * @throws TooManyReservationInRowException
	 * @throws TooManyReservationForEmailException
	 */
	public function canSendReservation($email)
	{
		if($this->getSentReservationCountForEmail($email) >= $this->maxCount) {
			throw new TooManyReservationForEmailException;
		}

		if($this->getSentReservationCountInRow() >= $this->maxCount) {
			throw new TooManyReservationInRowException;
		}

		$lastReservationTime = $this->getLastReservationTime();
		$fiveSecondAgo = (new DateTime)->modify("-{$this->minInterval} sec");
		if($lastReservationTime && $lastReservationTime > $fiveSecondAgo) {
			throw new InfringeMinIntervalReservationException;
		}

		return TRUE;
	}


	/**
	 * @return int
	 */
	protected function getSentReservationCountInRow()
	{
		return (int) $this->section->sentReservationCountInRow;
	}


	protected function increaseSentReservationCountInRow()
	{
		$this->section->sentReservationCountInRow++;
	}


	/**
	 * @param null $email
	 *
	 * @return mixed
	 */
	protected function getSentReservationCountForEmail($email = NULL)
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
	protected function increaseSentReservationCountForEmail($email)
	{
		if(isset($this->section->sentReservationCountByEmail[$email])) {
			$this->section->sentReservationCountByEmail[$email]++;
		} else {
			$this->section->sentReservationCountByEmail[$email] = 1;
		}
	}


	/**
	 * @param \DateTime $date
	 */
	protected function setLastReservationTime(\DateTime $date = NULL)
	{
		$this->section->lastReservationTime = $date;
	}


	/**
	 * @return \DateTime|NULL
	 */
	protected function getLastReservationTime()
	{
		return $this->section->lastReservationTime;
	}


	/**
	 * @return int
	 */
	public function getMinInterval()
	{
		return $this->minInterval;
	}


	/**
	 * @param int $minInterval
	 */
	public function setMinInterval($minInterval)
	{
		$this->minInterval = $minInterval;
	}


	/**
	 * @return int
	 */
	public function getMaxCount()
	{
		return $this->maxCount;
	}


	/**
	 * @param int $maxCount
	 */
	public function setMaxCount($maxCount)
	{
		$this->maxCount = $maxCount;
	}
}

class TooManyReservationInRowException extends \TooManyReservationException { }
class TooManyReservationForEmailException extends \TooManyReservationException { }
class TooManyReservationException extends \Exception { }
class InfringeMinIntervalReservationException extends \Exception { }
