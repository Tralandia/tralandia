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
use Tralandia\Reservation\Reservations;

class CalendarManager
{

	const KEY_FREE_CAPACITY = 'fc';
	const KEY_DATE = 'd';
	const KEY_YEAR = 'y';
	const KEY_DAY_Z = 'dz';

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
						$occupancy[$rentalId][$dateKey] = [
							self::KEY_FREE_CAPACITY => $rental->getMaxCapacity(),
							self::KEY_YEAR => $date->format('Y'),
							self::KEY_DAY_Z => $date->format('z'),
						];
					}

					$occupancy[$rentalId][$dateKey][self::KEY_FREE_CAPACITY] -= $unit->getMaxCapacity();
				}
			}
		}

		return $occupancy;
	}

}
