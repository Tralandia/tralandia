<?php

namespace Extras\Forms\Container;

use BaseModule\Components\CalendarControl;

class CalendarContainer extends BaseContainer
{

	/**
	 * @var \BaseModule\Components\CalendarControl
	 */
	protected $calendarControl;

	/**
	 * @var array
	 */
	protected $months = [];

	public function __construct($label = NULL, CalendarControl $calendarControl)
	{
		parent::__construct();

		$this->calendarControl = $calendarControl;
		$this->addHidden('data');

	}

	public function getMainControl()
	{
		return $this['data'];
	}

	/**
	 * @return array
	 */
	public function getMonths()
	{
		return $this->months;
	}

	protected function createComponentCalendar()
	{
		$comp = $this->calendarControl;

		return $comp;
	}

}

interface ICalendarContainerFactory {
	/**
	 * @param $label
	 *
	 * @return CalendarContainer
	 */
	public function create($label = NULL);
}
