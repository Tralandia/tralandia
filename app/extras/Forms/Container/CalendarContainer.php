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
	 * @param array $selectedDays
	 */
	public function __construct($label = NULL, Locale $locale, array $selectedDays = NULL)
	{
		parent::__construct();

		$this->calendarControl = new CalendarControl($locale, $selectedDays);

		$formattedDates = [];
		foreach($selectedDays as $day) {
			$formattedDates[] = $day->format('Y-m-d');
		}

		$this->addHidden('data')->setDefaultValue(implode(',', $formattedDates));


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

	public function getFormattedValues($asArray = FALSE)
	{
		$values = $this->getValues($asArray);

		$values['data'] = explode(',', $values['data']);

		$dateTime = [];
		foreach($values['data'] as $key => $day) {
			$dateTime[] = new \DateTime($day);
		}

		$values['data'] = $dateTime;

		return $values;
	}

}
