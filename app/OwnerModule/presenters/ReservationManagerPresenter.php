<?php

namespace OwnerModule;



use AdminModule\Forms\Form;
use BaseModule\Forms\SimpleForm;
use Entity\User\RentalReservation;
use Nette\Application\UI\Multiplier;
use Nette\DateTime;
use Nette\InvalidArgumentException;
use Nette\Utils\Arrays;
use Nette\Utils\Validators;
use Tralandia\BaseDao;
use Tralandia\Reservation\SearchQuery;

class ReservationManagerPresenter extends BasePresenter
{

	/**
	 * @autowire
	 * @var \Tralandia\Reservation\Reservations
	 */
	protected $reservations;

	/**
	 * @autowire
	 * @var \Tralandia\Reservation\ISearchQueryFactory
	 */
	protected $searchFactory;

	/**
	 * @var BaseDao
	 */
	protected $reservationDao;

	/**
	 * @autowire
	 * @var \BaseModule\Components\ICalendarControlFactory
	 */
	protected $calendarControlFactory;


	protected $rentalOrUnit;



	public function injectDependencies(\Nette\DI\Container $dic) {
		$this->reservationDao = $dic->getService('doctrine.default.entityManager')->getDao(RESERVATION_ENTITY);
	}


	public function actionAdd($from, $to)
	{
		/** @var $reservation RentalReservation */
		$reservation = $this->reservationDao->createNew();

		$reservation->setRental($this->loggedUser->getFirstRental());

		$reservation->setArrivalDate(DateTime::from($from));
		$reservation->setDepartureDate(DateTime::from($to));

		$this->em->persist($reservation);
		$this->em->flush();

		$this->redirect('ReservationEdit:', ['id' => $reservation->getId()]);
	}



	public function actionList($rental, $unit, $fulltext)
	{
		if(!$fulltext) {
			if($unit) {
				$this->rentalOrUnit = $this->findUnit($unit);
			} else if($rental) {
				$this->rentalOrUnit = $this->findRental($rental);
			}
		}

		if(!$this->rentalOrUnit)
			$this->rentalOrUnit = $this->loggedUser->getRentals()->toArray(); // aby hladal len userove rezervacie

	}

	public function renderList($rental, $unit, $fulltext)
	{

		$this->template->selectedRentalId = $rental;
		$this->template->selectedUnitId = $unit;
		$this->template->fulltext = $fulltext;
		$this->template->userRentals = $this->loggedUser->getRentals();


		$pastQuery = $this->searchFactory->create($this->rentalOrUnit, SearchQuery::PERIOD_PAST, NULL, $fulltext);
		$pastReservations = $this->reservationDao->fetch($pastQuery);

		if(isset($this->template->showPastReservations)) {
			$this->template->pastReservations = $pastReservations;
		} else {
			$presentQuery = $this->searchFactory->create($this->rentalOrUnit, SearchQuery::PERIOD_PRESENT, RentalReservation::STATUS_CONFIRMED, $fulltext);
			$futureQuery = $this->searchFactory->create($this->rentalOrUnit, SearchQuery::PERIOD_FUTURE, RentalReservation::STATUS_CONFIRMED, $fulltext);
			$openedQuery = $this->searchFactory->create($this->rentalOrUnit, NULL, RentalReservation::STATUS_OPENED, $fulltext);

			$this->template->presentReservations = $this->reservationDao->fetch($presentQuery);
			$this->template->futureReservations = $this->reservationDao->fetch($futureQuery);
			$this->template->openedReservations = $this->reservationDao->fetch($openedQuery);

			$this->template->pastReservationsCount = $pastReservations->count();
		}

	}


	public function handleGetPast()
	{

		$this->template->showPastReservations = TRUE;

		$this->invalidateControl('pastReservations');
	}


	public function actionDelete($id)
	{
		$reservation = $this->findReservation($id);
		$this->checkPermission($reservation, 'edit');

		$reservation->setStatus($reservation::STATUS_CANCELED);

		$this->em->flush($reservation);

		$this->redirect('list');
	}

	protected function createComponentNewReservationForm()
	{
		$form = $this->simpleFormFactory->create();

		$form->addText('date_from')
			->getControlPrototype()
				->placeholder('Dátum od');

		$form->addText('date_to')
			->getControlPrototype()
				->placeholder('Dátum do');

		$form->addSubmit('submit', 'Vytvoriť');

		$form->onSuccess[] = function(SimpleForm $form) {
			$values = $form->getValues();

			$this->redirect('add', ['from' => $values->date_from, 'to' => $values->date_to]);
		};

		return $form;
	}

	protected function createComponentCalendar()
	{
		$cb = function($rentalId) {
			$rental = $this->findRental($rentalId);
			return $this->calendarControlFactory->create($rental);
		};
		return new Multiplier($cb);
	}

}

