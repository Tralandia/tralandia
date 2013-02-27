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

	public function __construct($label = NULL)
	{
		parent::__construct();

		$this->calendarControl = new CalendarControl;
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
