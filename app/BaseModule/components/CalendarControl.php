<?php
namespace BaseModule\Components;

use Nette\DateTime;
use Tralandia\Dictionary\Translatable;
use Tralandia\Rental\CalendarManager;

class CalendarControl extends \BaseModule\Components\BaseControl {

	const VERSION_2 = 'v2';

	/**
	 * @var \Environment\Locale
	 */
	protected $locale;


	/**
	 * @var array|null
	 */
	protected $selectedDays;

	/**
	 * @var \Nette\ComponentModel\IContainer
	 */
	private $rental;


	public function __construct($rental, \Environment\Locale $locale) {
		parent::__construct();
		$this->locale = $locale;
		$this->selectedDays = $rental->getCalendar();
		$this->rental = $rental;
	}

	public function renderIframe($monthsCount, array $selectedDays = NULL, $version = self::VERSION_2){
		$this->render($monthsCount, $selectedDays, 'iframe', 0, $version);
	}

	public function renderEditable($monthsCount, array $selectedDays = NULL, $version = self::VERSION_2){
		$this->render($monthsCount, $selectedDays, 'editable', 0, $version);
	}

	public function render($monthsCount, array $selectedDays = NULL, $class = 'rentalDetail', $monthsOffset = 0, $version = self::VERSION_2)
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
		$unitsCapacity = $version == self::VERSION_2 ? $this->rental->getUnitsCapacity() : null;
		$rentalFreeCapacity = $version == self::VERSION_2 ? null : 1;
		$previousDay = NULL;
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


			while ($start <= $lastDayOfMonth) {
				$key = $start->format(CalendarManager::DATE_FORMAT_FOR_KEY);
				if(array_key_exists($key, $selectedDays)) {
					$month['days'][$key] = $previousDay = $selectedDays[$key];
				} else {
					$tempDay = CalendarManager::createDay($start, $unitsCapacity, $rentalFreeCapacity);
					if($previousDay) {
						$tempDay[CalendarManager::KEY_CLASS] = $previousDay[CalendarManager::KEY_NEXT_DAY_CLASS];
					}
					$month['days'][$key] = $tempDay;
					$previousDay = NULL;
				}
				CalendarManager::setDayTitle($month['days'][$key]);
				$start->modify('+1 day');
			}

			$months[$monthKey] = $month;
			$fromDate->modify('first day of next month');
		}

		$template->months = \Nette\ArrayHash::from($months);

		$template->render();
	}



}

interface ICalendarControlFactory {

	public function create($rental);
}
