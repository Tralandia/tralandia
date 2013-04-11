<?php

namespace FrontModule;

use Model\Rental\IRentalDecoratorFactory;
use FrontModule\Forms\Rental\IReservationFormFactory;
use Nette\Utils\Strings;


class RentalPresenter extends BasePresenter {

	/**
	 * @autowire
	 * @var \Model\Rental\IRentalDecoratorFactory
	 */
	protected $rentalDecoratorFactory;

	/**
	 * @autowire
	 * @var \LastSearch
	 */
	protected $lastSearch;

	/**
	 * @autowire
	 * @var \LastSeen
	 */
	protected $lastSeen;

	/**
	 * @autowire
	 * @var \FrontModule\Forms\Rental\IReservationFormFactory
	 */
	protected $reservationFormFactory;

	/**
	 * @autowire
	 * @var \BaseModule\Components\CalendarControl
	 */
	protected $calendarControl;


	public function actionDetail($rental) {
		if (!$rental) {
			throw new \Nette\InvalidArgumentException('$id argument does not match with the expected value');
		}

		$rentalService = $this->rentalDecoratorFactory->create($rental);
		$interviewAnswers = $rentalService->getInterviewAnswers($this->environment->primaryLocation->defaultLanguage);
		foreach ($interviewAnswers as $key => $answer) {
			if (preg_match("/^{\?.+\?}$/", $this->translate($answer->answer))) {
				unset($interviewAnswers[$key]);
			}
		}

		$locality = $rental->address->locality;
		$link = $this->link('//list', array('location' => $locality));
		$localitySeo = $this->seoFactory->create($link, $this->getLastCreatedRequest());

		$this->template->rental = $rental;
		$this->template->rentalService = $rentalService;
		$this->template->locality = $localitySeo;
		$this->template->interviewAnswers = $interviewAnswers;

		$this->template->teaser = $this->translate($rental->teaser);

		$firstAnswer = $rental->getFirstInterviewAnswer();
		if ($firstAnswer) {
			$this->template->firstAnswer = \Nette\Utils\Strings::truncate($this->translate($firstAnswer->answer), 200);
		} else {
			$this->template->firstAnswer = NULL;
		}

		$this->template->separateGroups = $rental->getSeparateGroups();
		$this->template->animalsAllowed = $rental->getAnimalsAllowed();
		$this->template->ownerAvailability = $rental->getOwnerAvailability();

		$this->template->dateUpdated = $rental->updated;

		$this->setLayout('detailLayout');

		$this->template->lastSearchResults = $this->getLastSearchResults($rental);
		$this->template->lastSeenRentals = $this->lastSeen->visit($rental)->getSeenRentals(12);
	}

	protected function getLastSearchResults($rental) {
		$lastSearch = $this->lastSearch;

		if (!$lastSearch->exists()) {
			return FALSE;
		}

		$bar = array();
		$bar['all'] = $lastSearch->getRentals();
		$bar['totalCount'] = count($bar['all']);
		$bar['currentKey'] = array_search($rental->id, $bar['all']);

		$start = $bar['currentKey']>5 ? ($bar['currentKey']-5) : 0;
		if (($left = count($bar['all']) - $start) < 12) {
			$start = $start - (12-$left);
		}

		$bar['all'] = array_slice($bar['all'], $start, 12);
		if (!isset($bar['currentKey'])) return FALSE;

		$barRentals = array();
		foreach($bar['all'] as $rental) {
			$barRentals[] = $this->context->rentalRepositoryAccessor->get()->find($rental);
		}

		$lastSearchResults = array();
		$lastSearchResults['rentals'] = $barRentals;
		$lastSearchResults['currentKey'] = $bar['currentKey']-($start > 0 ? $start : 0);
		$lastSearchResults['searchLink'] = $lastSearch->getUrl();
		$lastSearchResults['heading'] = $lastSearch->getHeading();
		$lastSearchResults['totalCount'] = $bar['totalCount'];

		if (isset($bar['all'][$lastSearchResults['currentKey']-1])) {
			$lastSearchResults['prevRental'] = $this->context->rentalRepositoryAccessor->get()->find($bar['all'][$lastSearchResults['currentKey']-1]);
		}

		if (isset($bar['all'][$lastSearchResults['currentKey']+1])) {
			$lastSearchResults['nextRental'] = $this->context->rentalRepositoryAccessor->get()->find($bar['all'][$lastSearchResults['currentKey']+1]);
		}

		if (!$lastSearchResults['totalCount']>1 && $this->template->navBarLastActive=='navBarSerchResults') {
			$this->template->navBarLastActive = 'navBarLastSeen';
		}

		return $lastSearchResults;
	}

	//
	// COMPONENTS
	//

	protected function createComponentReservationForm()
	{
		$form = $this->reservationFormFactory->create($this->getParameter('rental'));
		//$form->buildForm();

		$form->onSuccess[] = function ($form) {
			//$form->presenter->redirect('this');
			$form->presenter->template->fromSuccessMessage = 'o1029';
			$form->presenter->invalidateControl('reservationForm');
		};

		return $form;
	}

	protected function createComponentCalendar()
	{
		$comp = $this->calendarControl;

		return $comp;
	}
}
