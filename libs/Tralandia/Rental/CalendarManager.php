<?php
/**
 * This file is part of the tralandia.
 * User: macbook
 * Created at: 30/04/14 15:10
 */

namespace Tralandia\Rental;


use Entity\User\RentalReservation;
use Entity\User\User;
use Kdyby\Doctrine\EntityManager;
use Nette;
use Nette\Utils\Json;
use Tralandia\Reservation\Reservations;

class CalendarManager
{

	const KEY_FREE_CAPACITY = 'fc';
	const KEY_DATE = 'd';
	const KEY_YEAR = 'y';
	const KEY_DAY_Z = 'dz';
	const KEY_DAY_D = 'dd';
	const KEY_IS_WEEKDAY = 'iwd';
	const KEY_CLASS = 'c';
	const KEY_NEXT_DAY_CLASS = 'ndc';

	const DATE_FORMAT_FOR_KEY = 'Y-m-d';

	/**
	 * @var \Tralandia\Reservation\Reservations
	 */
	private $reservations;

	/**
	 * @var \Kdyby\Doctrine\EntityManager
	 */
	private $em;


	public function __construct(Reservations $reservations, EntityManager $em)
	{
		$this->reservations = $reservations;
		$this->em = $em;
	}


	public function update(User $user)
	{
		$occupancy = $this->calculateOccupancy($user);
		foreach($user->getRentals() as $rental) {
			if(array_key_exists($rental->getId(), $occupancy)) {
				$rental->updateCalendar($occupancy[$rental->getId()]);
			} else {
				$rental->updateCalendar([]);
			}
		}

		$this->em->flush();
	}


	public function calculateOccupancy(User $user)
	{
		$today = Nette\DateTime::from(strtotime('today'));
		$reservations = $this->reservations->getUsersReservations($user, RentalReservation::STATUS_CONFIRMED, $today);

		$interval = new \DateInterval('P1D');
		$occupancy = [];
		foreach($reservations as $reservation) {
			$units = $reservation->getUnits();
			$from = $reservation->getArrivalDate();
			$to = $reservation->getDepartureDate();

			/** @var $date \DateTime */
			foreach(new \DatePeriod($from, $interval, $to) as $date) {
				foreach($units as $unit) {
					$rental = $unit->getRental();
					$rentalId = $rental->getId();
					$dateKey = $date->format(self::DATE_FORMAT_FOR_KEY);
					if(!isset($occupancy[$rentalId][$dateKey])) {
						$occupancy[$rentalId][$dateKey] = self::createDay($date, $rental->getMaxCapacity());
					}

					$occupancy[$rentalId][$dateKey][self::KEY_FREE_CAPACITY] -= $unit->getMaxCapacity();
				}
			}
		}

		$occupancy = self::formatOccupancy($occupancy);

		return $occupancy;
	}


	public static function formatOccupancy($occupancy, $unsetDate = TRUE)
	{
		$getClassPart = function($capacity) {
			if($capacity === NULL) {
				return 0;
			} else {
				return $capacity ? '1' : '2';
			}
		};

		foreach($occupancy as &$days) {
			foreach($days as &$value) {
				$class = 's';

				$previousDay = clone $value[self::KEY_DATE];
				$previousDay->modify('-1 day');
				$previousKey = $previousDay->format(self::DATE_FORMAT_FOR_KEY);
				if(array_key_exists($previousKey, $days)) {
					$previousDayFreeCapacity = $days[$previousKey][self::KEY_FREE_CAPACITY];
				} else {
					$previousDayFreeCapacity = NULL;
				}
				$class .= $getClassPart($previousDayFreeCapacity);

				$class .= $nextDayClassPart = $getClassPart($value[self::KEY_FREE_CAPACITY]);

				$value[self::KEY_CLASS] = $class;
				$value[self::KEY_NEXT_DAY_CLASS] = "s{$nextDayClassPart}0";
				if($unsetDate) unset($value[self::KEY_DATE]);
			}
		}

		return $occupancy;
	}


	public static function createDay(\DateTime $date, $maxCapacity)
	{
		return [
			self::KEY_FREE_CAPACITY => $maxCapacity,
			self::KEY_YEAR => $date->format('Y'),
			self::KEY_DAY_Z => $date->format('z'),
			self::KEY_DAY_D => $date->format('d'),
			self::KEY_IS_WEEKDAY => in_array($date->format('N'), array(6,7)),
			self::KEY_DATE => clone $date,
		];
	}


	/**
	 * @return \DateTime[]
	 */
	public static function getCalendar($entity)
	{
		if(!$entity->getCalendarUpdated()) {
			return self::getOldCalendar($entity);
		}

		if(!is_array($entity->formattedCalendar)) {
			$days = Json::decode($entity->calendar, Json::FORCE_ARRAY);
			$todayZ = date('z');
			$thisYear = $entity->getCalendarUpdated()->format('Y');
			$daysTemp = [];
			foreach ($days as $key => $value) {
				if ($thisYear <= $value[CalendarManager::KEY_YEAR]
					&& $todayZ <= $value[CalendarManager::KEY_DAY_Z])
				{
					$daysTemp[$key] = $value;
					$daysTemp[$key][CalendarManager::KEY_DATE] = new \Nette\DateTime("$key 00:00:00");
				}
			}

			$entity->formattedCalendar = array_filter($daysTemp);
		}
		return $entity->formattedCalendar;
	}


	public static function getOldCalendar($entity)
	{
		if(!$entity->getOldCalendarUpdated()) {
			return [];
		}

		if(!is_array($entity->formattedOldCalendar)) {
			$days = array_filter(explode(',', $entity->oldCalendar));

			$todayZ = date('z');
			$calendarUpdatedZ = $entity->getOldCalendarUpdated()->format('z');
			$thisYear = $entity->getOldCalendarUpdated()->format('Y');
			$nextYear = $thisYear + 1;
			$daysTemp = [];

			foreach ($days as $key => $day) {
				if ($calendarUpdatedZ <= $day && $todayZ > $day) continue;
				$year = $calendarUpdatedZ <= $day ? $thisYear : $nextYear;
				$date = \Nette\DateTime::createFromFormat('z Y G-i-s', "$day $year 00-00-00");
				$dateKey = $date->format(CalendarManager::DATE_FORMAT_FOR_KEY);
				$daysTemp[$dateKey] = CalendarManager::createDay($date, 0);
			}

			$daysTemp = CalendarManager::formatOccupancy([1 => $daysTemp], false);
			$daysTemp = $daysTemp[1];

			$entity->formattedOldCalendar = array_filter($daysTemp);
		}

		return $entity->formattedOldCalendar;
	}

}
