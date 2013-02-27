<?php

namespace FrontModule;

use Nette\Application\BadRequestException;
use Nette\DateTime;

class CalendarIframePresenter extends BasePresenter {

	/**
	 * @autowire
	 * @var \BaseModule\Components\CalendarControl
	 */
	protected $calendarControl;

	public function actionDefault($rentalId, $monthsCount)
	{
		/** @var $rental \Entity\Rental\Rental */
		$rental = $this->rentalRepositoryAccessor->get()->find($rentalId);
		if(!$rental) {
			throw new BadRequestException;
		}

		//$selectedData = $rental->getCalendar();
		$selectedData = [
			new DateTime('2013-03-14'),
			new DateTime('2013-03-15'),
			new DateTime('2013-05-04'),
			new DateTime('2013-04-21'),
		];

		$this->template->rental = $rental;
		$this->template->monthsCount = $monthsCount;
		$this->template->selectedData = $selectedData;
	}

	protected function createComponentCalendar()
	{
		$comp = $this->calendarControl;

		return $comp;
	}


}
