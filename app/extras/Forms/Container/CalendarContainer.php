<?php

namespace Extras\Forms\Container;

class CalendarContainer extends BaseContainer
{

	/**
	 * @var array
	 */
	protected $months = [];

	public function __construct($label = NULL)
	{
		parent::__construct();

		$this->addHidden('data');
		$fromDate = new \Nette\DateTime(date('Y-m-01'));
		$months = 6;

		for($i=0;$i<$months;$i++) {
			$month = [];
			$start = clone $fromDate;
			$key = $start->format('Y-m');

			$month['title'] = $start->format('F Y');

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
				$month['days'][] = [
					'day' => $start->format('d'),
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

			$this->months[$key] = \Nette\ArrayHash::from($month);
			$fromDate->modify('first day of next month');
		}

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
}
