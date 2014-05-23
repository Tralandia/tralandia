<?php
namespace BaseModule\Components;

use Nette\DateTime;
use Tralandia\Rental\CalendarManager;

class CalendarControl extends \BaseModule\Components\BaseControl {

	/**
	 * @var \Environment\Locale
	 */
	protected $locale;


	/**
	 * @var array|null
	 */
	protected $selectedDays;



	public function __construct(\Environment\Locale $locale, array $selectedDays = NULL) {
		parent::__construct();
		$this->locale = $locale;
		$this->selectedDays = $selectedDays;
	}

	public function renderIframe($monthsCount, array $selectedDays = NULL, $version = 'v2'){
		$this->render($monthsCount, $selectedDays, 'iframe', 0, $version);
	}

	public function renderEditable($monthsCount, array $selectedDays = NULL, $version = 'v2'){
		$this->render($monthsCount, $selectedDays, 'editable', 0, $version);
	}

	public function render($monthsCount, array $selectedDays = NULL, $class = 'rentalDetail', $monthsOffset = 0, $version = 'v2')
	{
		$selectedDays = $selectedDays ? $selectedDays : $this->selectedDays;

		$template = $this->template;
		$template->version = $version;
		$template->containerClass = $class . ' ' . $version;

		$fromDate = new \Nette\DateTime(date('Y-m-01'));
		if($monthsOffset) {
			$fromDate->modify('+'.$monthsOffset.' month');
		}

		$months = [];
		for($i=0; $i<$monthsCount; $i++) {
			$month = [];
			$start = clone $fromDate;
			$monthKey = $start->format('Y-m');

			$monthName = $this->locale->getMonth($start->format('n'));
			$month['title'] = $monthName.' '.$start->format('Y');

			$month['blankDays'] = [];
			$firstDayOfMonth = $start->modifyClone()->format('N');
			if($firstDayOfMonth--) {
				$before = $start->modifyClone("-$firstDayOfMonth days");
				for( $b=0 ; $b<$firstDayOfMonth ; $b++ ) {
					$month['blankDays'][] = [
						'day' => $before->format('d'),
					];
					$before->modify('+1 day');
				}
			}

			$lastDayOfMonth = $start->modifyClone('last day of this month');

			$previousDay = NULL;
			while ($start <= $lastDayOfMonth) {
				$key = $start->format(CalendarManager::DATE_FORMAT_FOR_KEY);
				if(array_key_exists($key, $selectedDays)) {
					$month['days'][$key] = $previousDay = $selectedDays[$key];
					$month['days'][$key]['title'] = ;
				} else {
					$tempDay = CalendarManager::createDay($start);
					if($previousDay) {
						$tempDay[CalendarManager::KEY_CLASS] = $previousDay[CalendarManager::KEY_NEXT_DAY_CLASS];
					}
					$month['days'][$key] = $tempDay;
					$previousDay = NULL;
				}
				$start->modify('+1 day');
			}

			$months[$monthKey] = $month;
			$fromDate->modify('first day of next month');
		}

//		$months = $this->markSelectedDays($months, $selectedDays);

		$template->months = \Nette\ArrayHash::from($months);

		$template->render();
	}

	/**
	 * @param array $months
	 * @param array $selectedDays
	 *
	 * @return array
	 */
	protected function markSelectedDays(array $months, array $selectedDays = NULL)
	{
		if($selectedDays === NULL) return $months;

		foreach($selectedDays as $date) {
			$yearMonth = $date->format('Y-m');
			$day = $date->format('d');
			if(isset($months[$yearMonth]['days'][$day])) {
				$months[$yearMonth]['days'][$day]['selected'] = TRUE;
				$months[$yearMonth]['days'][$day]['status']['start'] = TRUE;
			}

			//$nextDay = $date->modifyClone('+1 day');
			$nextDay = c($date)->modify('+1 day');
			$yearMonth = $nextDay->format('Y-m');
			$day = $nextDay->format('d');
			if(isset($months[$yearMonth]['days'][$day])) {
				$months[$yearMonth]['days'][$day]['status']['end'] = TRUE;
			}
		}

		return $months;
	}


}
