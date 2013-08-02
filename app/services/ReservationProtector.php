<?php


use Doctrine\ORM\Query\ResultSetMapping;

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
	 * @var int
	 */
	protected $reservationCountPerDay = 10;

	/**
	 * @var Nette\Http\SessionSection
	 */
	protected $section;

	private $userBlackListRepository;

	/**
	 * @var Doctrine\ORM\EntityManager
	 */
	private $em;


	/**
	 * @param Doctrine\ORM\EntityManager $em
	 * @param \Nette\Http\Session $session
	 *
	 * @internal param $userBlackListRepository
	 */
	public function __construct(\Doctrine\ORM\EntityManager $em, \Nette\Http\Session $session)
	{
		$section = $session->getSection('reservationProtector');

		$this->section = $section;
		$this->em = $em;
		$this->userBlackListRepository = $em->getRepository(BLACK_LIST_ENTITY);
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
	 * @param $senderRemoteAddress
	 * @param Entity\Contact\Phone $phone
	 *
	 * @throws ReservationProtectorException
	 * @throws EmailIsOnBlackListException
	 * @return bool
	 */
	public function canSendReservation($email, $senderRemoteAddress, \Entity\Contact\Phone $phone = NULL)
	{
		if($this->userBlackListRepository->findOneByEmail($email)) {
			throw new EmailIsOnBlackListException;
		}

		$statement = $this->em->getConnection()->prepare("
SELECT IF (count(*) <= :reservationCountPerDay OR (count(*) > :reservationCountPerDay AND min(timediff(now(), created)) > '00:05:00'), 1, 0) as canSend
FROM user_rentalreservation
WHERE (senderEmail = :email OR senderPhone_id = :senderPhone OR senderRemoteAddress = :senderRemoteAddress)
AND timediff(now(), created) < :minDelta");

		$statement->bindValue(':reservationCountPerDay', $this->reservationCountPerDay);
		$statement->bindValue(':minDelta', '24:00:00');
		$statement->bindValue(':email', $email);
		$statement->bindValue(':senderPhone', $phone ? $phone->getId() : NULL);
		$statement->bindValue(':senderRemoteAddress', $senderRemoteAddress);

		$statement->execute();

		$canSend = $statement->fetchColumn();

		if(!$canSend) {
			throw new ReservationProtectorException();
		}

//		$lastReservationTimeForEmail = $this->getLastSentReservationForEmail($email);
//		$oneMonthAgo = (new DateTime)->modify("-1 month");
//		$sentReservationCountForEmail = count($this->getSentReservationForEmail($email));
//
//		if($sentReservationCountForEmail >= $this->maxCount && $lastReservationTimeForEmail && $lastReservationTimeForEmail >= $oneMonthAgo) {
//			throw new TooManyReservationForEmailException;
//		}
//
//		$lastReservationTime = $this->getLastReservationTime();
//		$fiveSecondAgo = (new DateTime)->modify("-{$this->minInterval} sec");
//		if($lastReservationTime && $lastReservationTime > $fiveSecondAgo) {
//			throw new InfringeMinIntervalReservationException;
//		}

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

class ReservationProtectorException extends \Exception { }
class EmailIsOnBlackListException extends ReservationProtectorException { }
class TooManyReservationForEmailException extends ReservationProtectorException { }
class InfringeMinIntervalReservationException extends ReservationProtectorException { }
