<?php

namespace Extras\Forms\Container;

use BaseModule\Components\CalendarControl;
use Environment\Locale;

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

	/**
	 * @param null $label
	 * @param \Environment\Locale $locale
	 */
	public function __construct($label = NULL, Locale $locale)
	{
		parent::__construct();

		$this->calendarControl = new CalendarControl($locale);
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
