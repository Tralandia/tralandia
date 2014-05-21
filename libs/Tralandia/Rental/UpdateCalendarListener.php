<?php
/**
 * This file is part of the Tralandia.
 * User: david
 * Created at: 7/22/13 3:35 PM
 */

namespace Tralandia\SearchCache;


use Doctrine\ORM\EntityManager;
use Entity\Rental\Rental;
use Robot\IUpdateRentalSearchCacheRobotFactory;
use Nette;
use Nette\Caching\Cache;
use Tralandia\Rental\CalendarManager;

class UpdateCalendarListener implements \Kdyby\Events\Subscriber
{


	/**
	 * @var \Tralandia\Rental\CalendarManager
	 */
	private $calendarManager;


	public function __construct(CalendarManager $calendarManager)
	{

		$this->calendarManager = $calendarManager;
	}

	public function getSubscribedEvents()
	{
		return [
		];
	}


	public function onReservationEdit(\Entity\User\RentalReservation $reservation)
	{
		$this->calendarManager->update($reservation->getSomeRental()->getUser());

	}


}
