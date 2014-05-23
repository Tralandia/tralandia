<?php

namespace FrontModule;

use Nette\DateTime;
use Nette\Application\BadRequestException;

class CalendarIframePresenter extends BasePresenter {


	public function actionDefault($rental, $months)
	{
		$this->template->monthsCount = $months;

		$selectedData = $rental->getCalendar();
		$this->template->selectedData = $selectedData;

		$params = $this->request->getParameters();
		$this->template->version = $params['version'] ?: 'old';
	}

	protected function createComponentCalendar(\BaseModule\Components\ICalendarControlFactory $factory)
	{
		$comp = $factory->create($this->getParameter('rental'));

		return $comp;
	}


}
