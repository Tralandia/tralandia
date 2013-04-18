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
		$section = $session->getSection('reservationProtector');

		$this->section = $section;
	}


	/**
	 * @param $email
	 */
	public function newReservationSent($email)
	{
		$date = new \DateTime;
		$this->setLastReservationTime($date);
		$this->newSentReservationForEmail($email, $date);
	}


	/**
	 * @param $email
	 *
	 * @return bool
	 * @throws InfringeMinIntervalReservationException
	 * @throws TooManyReservationForEmailException
	 */
	public function canSendReservation($email)
	{
		$lastReservationTimeForEmail = $this->getLastSentReservationForEmail($email);
		$oneMonthAgo = (new DateTime)->modify("-1 month");
		$sentReservationCountForEmail = count($this->getSentReservationForEmail($email));

		if($sentReservationCountForEmail >= $this->maxCount && $lastReservationTimeForEmail && $lastReservationTimeForEmail >= $oneMonthAgo) {
			throw new TooManyReservationForEmailException;
		}

		$lastReservationTime = $this->getLastReservationTime();
		$fiveSecondAgo = (new DateTime)->modify("-{$this->minInterval} sec");
		if($lastReservationTime && $lastReservationTime > $fiveSecondAgo) {
			throw new InfringeMinIntervalReservationException;
		}

		return TRUE;
	}


	public function reset()
	{
		$this->section->remove();
	}


	/**
	 * @param null $email
	 *
	 * @return mixed
	 */
	protected function getSentReservationForEmail($email = NULL)
	{
		if(!$email) {
			return $this->section->sentReservationByEmail;
		} else {
			if(isset($this->section->sentReservationByEmail[$email])) {
				return $this->section->sentReservationByEmail[$email];
			} else {
				return [];
			}
		}
	}


	/**
	 * @param $email
	 *
	 * @return mixed
	 */
	protected function getLastSentReservationForEmail($email)
	{
		$list = $this->getSentReservationForEmail($email);
		if(!is_array($list)) return NULL;
		return end($list);
	}


	/**
	 * @param $email
	 * @param DateTime $date
	 */
	protected function newSentReservationForEmail($email, DateTime $date)
	{
		$this->section->sentReservationByEmail[$email][] = $date;
		if(count($this->section->sentReservationByEmail[$email]) > $this->maxCount) {
			$slice = array_slice($this->section->sentReservationByEmail[$email], 0, $this->maxCount);
			$this->section->sentReservationByEmail[$email] = $slice;
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

class TooManyReservationForEmailException extends \Exception { }
class InfringeMinIntervalReservationException extends \Exception { }
