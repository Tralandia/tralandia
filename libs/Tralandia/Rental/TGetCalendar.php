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
	 * @return bool
	 */
	public function useOldCalendar()
	{
		$cu = $this->getCalendarUpdated();
		return !isset($cu);
	}

	/**
	 * @return bool
	 */
	public function calendarVersion()
	{
		return $this->useOldCalendar() ? 'old' : 'v2';
	}

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
				$daysTemp[$dateKey] = CalendarManager::createDay($date);
			}

			$daysTemp = CalendarManager::formatOccupancy([1 => $daysTemp], false);
			$daysTemp = $daysTemp[1];

			$this->formattedOldCalendar = array_filter($daysTemp);
		}

		return $this->formattedOldCalendar;
	}



	/**
	 * @param array $calendar
	 * @param \DateTime $updated
	 *
	 * @return $this
	 */
	public function updateCalendar(array $calendar, \DateTime $updated = NULL)
	{
		if($updated === NULL) {
			$updated = new \DateTime();
		}

		$this->setCalendarUpdated($updated);
		$this->setCalendar($calendar);

		return $this;
	}


	/**
	 * @param array|\DateTime[]
	 *
	 * @return \Entity\Rental\Rental
	 */
	public function setCalendar(array $calendar)
	{
		$calendar = Json::encode($calendar);

		$this->calendar = $calendar;
		$this->formattedCalendar = NULL;

		return $this;
	}


	/**
	 * @param array $calendar
	 * @param \DateTime $updated
	 *
	 * @return $this
	 */
	public function updateOldCalendar(array $calendar, \DateTime $updated = NULL)
	{
		if($updated === NULL) {
			$updated = new \DateTime();
		}

		$this->setOldCalendarUpdated($updated);
		$this->setOldCalendar($calendar);

		return $this;
	}


	/**
	 * @param array|\DateTime[]
	 *
	 * @return \Entity\Rental\Rental
	 */
	public function setOldCalendar(array $calendar)
	{
		foreach ($calendar as $key => $date) {
			$calendar[$key] = $date->format('z');
		}

		$this->oldCalendar = ',' . (implode(',', $calendar)) . ',';
		$this->formattedOldCalendar = NULL;

		return $this;
	}


	/**
	 * @param \DateTime $from
	 * @param \DateTime $to
	 *
	 * @return bool
	 */
	public function isAvailable(\DateTime $from, \DateTime $to = NULL)
	{
		$calendar = $this->getCalendar();

		if(!count($calendar)) return TRUE;

		if($to === NULL) $to = clone $from;

		$from->modify('midnight');
		while($from <= $to) {
			if(in_array($from, $calendar)) return FALSE;
			$from->modify('next day');
		}

		return TRUE;
	}


	/**
	 * @param \DateTime
	 * @return \Entity\Rental\Rental
	 */
	public function setCalendarUpdated(\DateTime $calendarUpdated)
	{
		if($this instanceof \Tralandia\Rental\Rental) {
			$this->row->calendarUpdated = $calendarUpdated;
		} else {
			$this->calendarUpdated = $calendarUpdated;
		}

		return $this;
	}

	/**
	 * @return \DateTime|NULL
	 */
	public function getCalendarUpdated()
	{
		return $this instanceof \Tralandia\Rental\Rental ? $this->row->calendarUpdated : $this->calendarUpdated;
	}

	/**
	 * @param \DateTime
	 * @return \Entity\Rental\Rental
	 */
	public function setOldCalendarUpdated(\DateTime $calendarUpdated)
	{
		if($this instanceof \Tralandia\Rental\Rental) {
			$this->row->oldCalendarUpdated = $calendarUpdated;
		} else {
			$this->oldCalendarUpdated = $calendarUpdated;
		}

		return $this;
	}

	/**
	 * @return \DateTime|NULL
	 */
	public function getOldCalendarUpdated()
	{
		return $this instanceof \Tralandia\Rental\Rental ? $this->row->oldCalendarUpdated : $this->oldCalendarUpdated;
	}


	public function getLastCalendarUpdate()
	{
		return $this->useOldCalendar() ? $this->getOldCalendarUpdated() : $this->getCalendarUpdated();
	}



}
