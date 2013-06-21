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

	public function actionDefault($rental, $months)
	{
		$selectedData = $rental->getCalendar();

		$this->template->monthsCount = $months;
		$this->template->selectedData = $selectedData;
	}

	protected function createComponentCalendar()
	{
		$comp = $this->calendarControl;

		return $comp;
	}


}
