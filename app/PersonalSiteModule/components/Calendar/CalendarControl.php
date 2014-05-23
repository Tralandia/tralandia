<?php
/**
 * This file is part of the tralandia.
 * User: macbook
 * Created at: 05/03/14 14:06
 */

namespace PersonalSiteModule\Calendar;


use Nette;
use PersonalSiteModule\BaseControl;
use Tralandia\Rental\Rental;

class CalendarControl extends BaseControl
{

	/**
	 * @var Rental
	 */
	private $rental;

	/**
	 * @var \BaseModule\Components\ICalendarControlFactory
	 */
	protected $calendarControlFactory;


	public function __construct(Rental $rental, \BaseModule\Components\ICalendarControlFactory $calendarControlFactory)
	{
		parent::__construct();
		$this->rental = $rental;
		$this->calendarControlFactory = $calendarControlFactory;
	}

	public function render()
	{
		$rental = $this->rental;

		$this->template->rental = $rental;

		$this->template->render();
	}

	protected function createComponentCalendar()
	{
		$comp = $this->calendarControlFactory->create($this->rental);

		return $comp;
	}


}

interface ICalendarControlFactory
{
	public function create(Rental $rental);
}
