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
		$this->template->monthsCount = $months;
		
		$selectedData = $rental->getCalendar();
		$this->template->selectedData = $selectedData;

		$params = $this->request->getParameters();
		$this->template->version = $params['version'] ?: 'old';
	}

	protected function createComponentCalendar()
	{
		$comp = $this->calendarControl;

		return $comp;
	}


}
