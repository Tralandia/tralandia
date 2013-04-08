<?php
namespace Tests\Rental;

use Nette, Extras;


/**
 * @backupGlobals disabled
 */
class CalendarTest extends \Tests\TestCase
{

	/**
	 * @var array
	 */
	public $calendar;

	/**
	 * @var \Entity\Rental\Rental
	 */
	protected $rental;

	protected function setUp() {
		$rentalRepository = $this->getContext()->rentalRepositoryAccessor->get();

		/** @var $rental \Entity\Rental\Rental */
		$rental = $rentalRepository->createNew();

		$year = date('Y') + 1;
		$this->calendar = [
			new \DateTime("$year-01-03"),
			new \DateTime("$year-01-04"),
			new \DateTime("$year-01-05"),
		];

		$rental->updateCalendar($this->calendar);

		$this->rental = $rental;
	}

	public function testBase() {
		$calendar = $this->rental->getCalendar();

		$this->assertEquals($this->calendar, $calendar);
	}

	public function testIsRentalAvailable()
	{
		$reserved = $this->calendar[0];
		$free = cl($reserved)->modify('+1 month');

		$this->assertFalse($this->rental->isAvailable($reserved));
		$this->assertTrue($this->rental->isAvailable($free));
	}

}
