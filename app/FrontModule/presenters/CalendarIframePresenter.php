<?php

namespace FrontModule;

use Nette\Application\BadRequestException;

class CalendarIframePresenter extends BasePresenter {

	/**
	 * @autowire
	 * @var \BaseModule\Components\CalendarControl
	 */
	protected $calendarControl;

	public function actionDefault($rentalId, $monthsCount = 1)
	{
		$rental = $this->rentalRepositoryAccessor->get()->find($rentalId);
		if(!$rental) {
			throw new BadRequestException;
		}

		$this->template->rental = $rental;
		$this->template->monthsCount = $monthsCount;
	}

	protected function createComponentCalendar()
	{
		$comp = $this->calendarControl;

		return $comp;
	}


}
