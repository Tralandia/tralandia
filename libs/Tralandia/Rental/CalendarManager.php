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
use Tralandia\Dictionary\Translatable;
use Tralandia\Reservation\Reservations;

class CalendarManager
{

	const KEY_FREE_CAPACITY = 'fc';
	const KEY_FREE_UNITS = 'fu';
	const KEY_BOOKED_UNITS = 'bu';
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


	public function getAvailableUnits(User $user, \DateTime $from = NULL, \DateTime $to = NULL, RentalReservation $excludeReservation = NULL)
	{
		$occupancy = $this->calculateOccupancy($user, $excludeReservation);

		$available = [];
		foreach($user->getRentals() as $rental) {
			$available[$rental->id] = $rental->getUnitsCapacity();
			if(isset($occupancy[$rental->id])) {
				foreach($occupancy[$rental->id] as $day) {
					if($from and $to and $from <= $day[self::KEY_DATE] and $to >= $day[self::KEY_DATE]) {
						foreach($day[self::KEY_BOOKED_UNITS] as $bookedUnitId => $value) {
							unset($available[$rental->id][$bookedUnitId]);
						}
					}
				}
			}
		}

		$available = array_filter($available, function($v) {return count($v);});

		return $available;
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


	public function calculateOccupancy(User $user, RentalReservation $excludeReservation = NULL)
	{
		$today = Nette\DateTime::from(strtotime('today'));
		$reservations = $this->reservations->getUsersReservations($user, RentalReservation::STATUS_CONFIRMED, $today);

		$interval = new \DateInterval('P1D');
		$occupancy = [];
		foreach($reservations as $reservation) {
			if($excludeReservation and $reservation->id == $excludeReservation->id) continue;
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
						$occupancy[$rentalId][$dateKey] = self::createDay($date, $rental->getUnitsCapacity());
					}

					self::unitBooked($occupancy[$rentalId][$dateKey], $unit->getId());
				}
			}
		}

		$occupancy = self::formatOccupancy($occupancy, !isset($excludeReservation));

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


	public static function createDay(\DateTime $date, array $freeUnitsCapacity = NULL, $freeCapacity = 0)
	{
		return [
			self::KEY_FREE_CAPACITY => !$freeUnitsCapacity ? $freeCapacity : array_sum($freeUnitsCapacity), // aby sme predisli BC break-u
			self::KEY_FREE_UNITS => $freeUnitsCapacity,
			self::KEY_BOOKED_UNITS => [],
			self::KEY_YEAR => $date->format('Y'),
			self::KEY_DAY_Z => $date->format('z'),
			self::KEY_DAY_D => $date->format('d'),
			self::KEY_IS_WEEKDAY => in_array($date->format('N'), array(6,7)),
			self::KEY_DATE => clone $date,
		];
	}

	public static function unitBooked(&$data, $unit)
	{
		$data[self::KEY_BOOKED_UNITS][$unit] = $data[self::KEY_FREE_UNITS][$unit];
		unset($data[self::KEY_FREE_UNITS][$unit]);
		$data[self::KEY_FREE_CAPACITY] = array_sum($data[self::KEY_FREE_UNITS]);
	}


	public static function setDayTitle(&$day)
	{
		if($day[self::KEY_FREE_UNITS] == null) {
			if($day[self::KEY_FREE_CAPACITY] == 0) {
				$day['title'] = Translatable::from([790459]);
			} else {
				$day['title'] = Translatable::from([790452]);
			}
		} else {
			if($day[self::KEY_FREE_CAPACITY] == 0) {
				$day['title'] = Translatable::from([790459]);
			} else {
				$units = implode('+', $day[self::KEY_FREE_UNITS]);
				if(count($day[self::KEY_BOOKED_UNITS])) {
					$day['title'] = Translatable::from([790458], '(', [231, 2], ": $units)");
				} else {
					$day['title'] = Translatable::from([790452], '(', [231, 2], ": $units)");
				}
			}
		}
	}

}
