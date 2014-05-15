<?php
namespace Tests\Rental;

use Nette, Extras;


/**
 * @backupGlobals disabled
 */
class CalendarManagerTest extends \Tests\TestCase
{


	/**
	 * @var \Tralandia\Rental\CalendarManager
	 */
	protected $calendarManager;


	protected function setUp()
	{
		$now = new \Nette\DateTime();
		$variables = [
			'###arrivalDate1###' => $now->modifyClone('+1 day')->format('Y-m-d 00:00:00'),
			'###arrivalDate2###' => $now->modifyClone('+10 day')->format('Y-m-d 00:00:00'),
			'###arrivalDate3###' => $now->modifyClone('+20 day')->format('Y-m-d 00:00:00'),
			'###departureDate1###' => $now->modifyClone('+2 day')->format('Y-m-d 00:00:00'),
			'###departureDate2###' => $now->modifyClone('+12 day')->format('Y-m-d 00:00:00'),
			'###departureDate3###' => $now->modifyClone('+23 day')->format('Y-m-d 00:00:00'),
		];
		$this->newDataSet(__DIR__ . '/CalendarManagerTest.sql', $variables);

		$this->calendarManager = $this->getContext()->getByType('\Tralandia\Rental\CalendarManager');
	}

	public function testCalculateOccupancy()
	{
		$user = $this->findUser(1);

		$occupancy = $this->calendarManager->calculateOccupancy($user);

		$rentalOccupancy = Nette\Utils\Arrays::get($occupancy, 1, array());
		$this->assertEquals(1, count($rentalOccupancy));

		$rentalOccupancy = Nette\Utils\Arrays::get($occupancy, 2, array());
		$this->assertEquals(5, count($rentalOccupancy));
	}

	public function testUpdateCalendar()
	{
		$user = $this->findUser(1);

		$this->calendarManager->update($user);

		$rental = $this->findRental(1);
		$calendar = $rental->getCalendar();

		$this->assertEquals(1, 1);

	}


	public function testOldCalendar()
	{
		$rental = $this->findRental(3);

		$calendar = $rental->getCalendar();

		$this->assertEquals([], $calendar);
	}


}
