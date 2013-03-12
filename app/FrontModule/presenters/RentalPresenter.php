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

		$dateUpdated = new \Nette\DateTime();
		$dateUpdated->from($rental->updated);
		$this->template->dateUpdated = $dateUpdated->__toString();

		$calendarUpdated = new \Nette\DateTime();
		$calendarUpdated->from($rental->calendarUpdated);
		$this->template->calendarUpdated = $calendarUpdated->__toString();
		$this->setLayout('detailLayout');

		$this->template->navBar = $this->getNavigationBar($rental);
	}

	protected function getNavigationBar($rental) {
		$lastSearch = $this->lastSearch;

		if (!$lastSearch->exists()) {
			return FALSE;
		}

		$bar = array();
		$bar['all'] = $lastSearch->getRentals();
		if (count($bar['all']) < 2) return FALSE;
		$bar['currentKey'] = array_search($rental->id, $bar['all']);
		if (!isset($bar['currentKey'])) return FALSE;
		$bar['firstKey'] = $bar['currentKey'] < 9 ? 0 : $bar['currentKey'] - 8;
		if ($bar['firstKey'] < 0) $bar['firstKey'] = 0;

		$bar['placeholderCount'] = $bar['currentKey'] < 8 ? 8 - $bar['currentKey'] : 0;
		
		$barRentals = array();
		for ($i = 0; $i < $bar['placeholderCount']; $i++) {
			$barRentals[] = FALSE;
		}
		$i = $bar['firstKey'];
		
		while (count($barRentals) < 18) {
			if (!isset($bar['all'][$i])) break;
			$barRentals[] = $this->context->rentalRepositoryAccessor->get()->find($bar['all'][$i]);
			$i++;
		}
		
		$navBar = array();
		$navBar['rentals'] = $barRentals;
		$navBar['searchLink'] = $lastSearch->getUrl();
		$navBar['heading'] = $lastSearch->getHeading();
		$navBar['currentIndex'] = $bar['currentKey']+1;
		$navBar['totalCount'] = count($bar['all']);

		if (isset($bar['all'][$bar['currentKey']-1])) {
			$navBar['prevRental'] = $this->context->rentalRepositoryAccessor->get()->find($bar['all'][$bar['currentKey']-1]);
		}

		if (isset($bar['all'][$bar['currentKey']+1])) {
			$navBar['nextRental'] = $this->context->rentalRepositoryAccessor->get()->find($bar['all'][$bar['currentKey']+1]);
		}

		return $navBar;
	}

	//
	// COMPONENTS
	//

	protected function createComponentReservationForm()
	{
		$form = $this->reservationFormFactory->create($this->getParameter('rental'));
		//$form->buildForm();

		$form->onSuccess[] = function ($form) { 

			$form->presenter->redirect('this');
			//$form->presenter->invalidateControl('reservationForm');
			//$form->presenter->sendPayload();
		};
	
		return $form;
	}

	protected function createComponentCalendar()
	{
		$comp = $this->calendarControl;

		return $comp;
	}
}
