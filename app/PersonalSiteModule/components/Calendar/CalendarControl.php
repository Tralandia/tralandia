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
	protected $rental;

	/**
	 * @var \BaseModule\Components\CalendarControl
	 */
	protected $calendarControl;


	public function __construct(Rental $rental, \BaseModule\Components\CalendarControl $calendarControl)
	{
		parent::__construct();
		$this->rental = $rental;
		$this->calendarControl = $calendarControl;
	}

	public function render()
	{
		$rental = $this->rental;

		$this->template->rental = $rental;

		$this->template->render();
	}

	protected function createComponentCalendar()
	{
		$comp = $this->calendarControl;

		return $comp;
	}


}

interface ICalendarControlFactory
{
	public function create(Rental $rental);
}
