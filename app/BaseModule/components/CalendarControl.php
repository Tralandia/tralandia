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
		$template->containerClass = 'iframe';
		$this->render($monthsCount,$selectedDays);
	}

	public function renderEditable($monthsCount, array $selectedDays = NULL){
		$template = $this->template;
		$template->containerClass = 'editable';
		$this->render($monthsCount,$selectedDays);
	}

	public function render($monthsCount, array $selectedDays = NULL)
	{
		$selectedDays = $selectedDays ? $selectedDays : $this->selectedDays;

		$template = $this->template;
		if(!isset($template->containerClass)){
			$template->containerClass = 'rentalDetail';
		}


		$fromDate = new \Nette\DateTime(date('Y-m-01'));
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

			while ($start <= $lastDayOfMonth) {
				$day = $start->format('d');
				if(isset($selectedDays["$key-$day"])) {

				}
				$month['days'][$day] = [
					'day' => $day,
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
