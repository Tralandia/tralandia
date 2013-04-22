<?php

namespace FrontModule;

use Nette\DateTime;
use Nette\Application\BadRequestException;

class CalendarIframePresenter extends BasePresenter {

	/**
	 * @autowire
	 * @var \BaseModule\Components\CalendarControl
	 */
	protected $calendarControl;

	public function actionDefault($rentalId, $monthsCount)
	{
		//$selectedData = $rental->getCalendar();
		$selectedData = [
			new DateTime('2013-03-14'),
			new DateTime('2013-03-15'),
			new DateTime('2013-05-04'),
			new DateTime('2013-04-21'),
		];

		$this->template->monthsCount = $monthsCount;
		$this->template->selectedData = $selectedData;
	}

	protected function createComponentCalendar()
	{
		$comp = $this->calendarControl;

		return $comp;
	}


}
