<?php
namespace BaseModule\Components;

use Nette\DateTime;

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

	public function renderIframe($monthsCount, array $selectedDays = NULL){
		$template = $this->template;
		$this->render($monthsCount,$selectedDays, 'iframe');
	}

	public function renderEditable($monthsCount, array $selectedDays = NULL){
		$template = $this->template;
		$this->render($monthsCount,$selectedDays, 'editable');
	}

	public function render($monthsCount, array $selectedDays = NULL, $class = 'rentalDetail', $monthsOffset = 0)
	{
		$selectedDays = $selectedDays ? $selectedDays : $this->selectedDays;

		$template = $this->template;
		$template->containerClass = $class;

		$fromDate = new \Nette\DateTime(date('Y-m-01'));
		if($monthsOffset) {
			$fromDate->modify('+'.$monthsOffset.' month');
		}
		$months = [];
		for($i=0; $i<$monthsCount; $i++) {
			$month = [];
			$start = clone $fromDate;
			$key = $start->format('Y-m');

			$monthName = $this->locale->getMonth($start->format('n'));
			$month['title'] = $monthName.' '.$start->format('Y');

			$month['daysBefore'] = [];
			$firstDayOfMonth = $start->modifyClone()->format('N');
			if($firstDayOfMonth--) {
				$before = $start->modifyClone("-$firstDayOfMonth days");
				for( $b=0 ; $b<$firstDayOfMonth ; $b++ ) {
					$month['daysBefore'][] = [
						'day' => $before->format('d'),
					];
					$before->modify('+1 day');
				}
			}

			$lastDayOfMonth = $start->modifyClone('last day of this month');
			$now = new \Nette\DateTime();

			while ($start <= $lastDayOfMonth) {
				$day = $start->format('d');
				if(isset($selectedDays["$key-$day"])) {

				}
				$month['days'][$day] = [
					'day' => $day,
					'isWeekday' => (
						in_array($start->format('N'), array(6,7))
						? true 
						: false
					),
					'isPast' => ($now > $start)
				];
				$start->modify('+1 day');
			}

			$month['daysAfter'] = [];
			$lastDayOfMonthN = $lastDayOfMonth->format('N');
			if($lastDayOfMonthN < 7) {
				for( $a=$lastDayOfMonthN ; $a<7 ; $a++ ) {
					$lastDayOfMonth->modify('+1 day');
					$month['daysAfter'][] = [
						'day' => $lastDayOfMonth->format('d'),
					];
				}
			}

			$months[$key] = $month;
			$fromDate->modify('first day of next month');
		}

		$months = $this->markSelectedDays($months, $selectedDays);

		$this->template->months = \Nette\ArrayHash::from($months);

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
