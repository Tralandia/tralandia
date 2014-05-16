<?php

namespace Tralandia\Rental;

use Nette\Utils\Json;
use Tralandia\Rental\CalendarManager;

trait TGetCalendar
{

	/**
	 * toto nieje stlpec v DB je to len pomocna premenna
	 * @var array
	 */
	protected $formattedCalendar;

	/**
	 * toto nieje stlpec v DB je to len pomocna premenna
	 * @var array
	 */
	protected $formattedOldCalendar;

	/**
	 * @return array|\DateTime[]
	 */
	public function getCalendar()
	{
		/** @var $this \Tralandia\Rental\Rental */
		if(!$this->getCalendarUpdated()) {
			return $this->getOldCalendar();
		}

		if(!is_array($this->formattedCalendar)) {
			if($this instanceof \Tralandia\Rental\Rental) {
				$calendar = $this->row->calendar;
			} else {
				$calendar = $this->calendar;
			}
			$days = Json::decode($calendar, Json::FORCE_ARRAY);
			$todayZ = date('z');
			$thisYear = $this->getCalendarUpdated()->format('Y');
			$daysTemp = [];
			foreach ($days as $key => $value) {
				if ($thisYear <= $value[CalendarManager::KEY_YEAR]
					&& $todayZ <= $value[CalendarManager::KEY_DAY_Z])
				{
					$daysTemp[$key] = $value;
					$daysTemp[$key][CalendarManager::KEY_DATE] = new \Nette\DateTime("$key 00:00:00");
				}
			}

			$this->formattedCalendar = array_filter($daysTemp);
		}
		return $this->formattedCalendar;
	}


	public function getOldCalendar()
	{
		/** @var $this \Tralandia\Rental\Rental */
		if(!$this->getOldCalendarUpdated()) {
			return [];
		}

		if(!is_array($this->formattedOldCalendar)) {
			if($this instanceof \Tralandia\Rental\Rental) {
				$oldCalendar = $this->row->oldCalendar;
			} else {
				$oldCalendar = $this->oldCalendar;
			}
			$days = array_filter(explode(',', $oldCalendar));

			$todayZ = date('z');
			$calendarUpdatedZ = $this->getOldCalendarUpdated()->format('z');
			$thisYear = $this->getOldCalendarUpdated()->format('Y');
			$nextYear = $thisYear + 1;
			$daysTemp = [];

			foreach ($days as $key => $day) {
				if ($calendarUpdatedZ <= $day && $todayZ > $day) continue;
				$year = $calendarUpdatedZ <= $day ? $thisYear : $nextYear;
				$date = \Nette\DateTime::createFromFormat('z Y G-i-s', "$day $year 00-00-00");
				$dateKey = $date->format(CalendarManager::DATE_FORMAT_FOR_KEY);
				$daysTemp[$dateKey] = CalendarManager::createDay($date, 0);
			}

			$daysTemp = CalendarManager::formatOccupancy([1 => $daysTemp], false);
			$daysTemp = $daysTemp[1];

			$this->formattedOldCalendar = array_filter($daysTemp);
		}

		return $this->formattedOldCalendar;
	}

}
