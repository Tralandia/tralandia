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
	public function __construct($label = NULL, Locale $locale, $rental)
	{
		parent::__construct();

		$this->calendarControl = new CalendarControl($rental, $locale);

		$formattedDates = [];
		$selectedDays = $rental->getCalendar();
		foreach($selectedDays as $key => $day) {
			$formattedDates[] = $key;
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

		$values['data'] = array_filter(explode(',', $values['data']));

		$dateTime = [];
		foreach($values['data'] as $key => $day) {
			$dateTime[] = new \DateTime($day);
		}

		$values['data'] = $dateTime;

		return $values;
	}

}
