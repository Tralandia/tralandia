<?php

namespace OwnerModule;



use Entity\User\RentalReservation;
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
	 * @persistent
	 * @var int
	 */
	public $rental;

	/**
	 * @persistent
	 * @var string
	 */
	public $period;

	/**
	 * @persistent
	 * @var string
	 */
	public $fulltext;


	protected $timePeriods = [
		1 => ['past', NULL, 'today'],
		2 => ['future', 'today', NULL],
	];

	public function injectDependencies(\Nette\DI\Container $dic) {
		$this->reservationDao = $dic->getService('doctrine.default.entityManager')->getDao(RESERVATION_ENTITY);
	}


	public function actionAdd()
	{
		/** @var $reservation \Entity\User\RentalReservation */
		$reservation = $this->reservationDao->createNew();

		$this->em->persist($reservation);
		$this->em->flush();

		$this->redirect('ReservationEdit:', ['id' => $reservation->getId()]);
	}

	public function actionChangeStatus($id, $status)
	{
		$reservation = $this->findReservation($id);
		$this->checkPermission($reservation, 'edit');

		$reservation->setStatus(\Entity\User\RentalReservation::STATUS_CANCELED);

		$this->em->flush($reservation);

		$this->invalidateControl('reservation-'.$reservation->id);
		$this->sendPayload();
	}


	public function actionList()
	{
		if($this->rental) {
			$rentals = [$this->findRental($this->rental)];
		} else {
			$rentals = $this->loggedUser->getRentals()->toArray();
		}
		$query = $this->searchFactory->create($rentals, $this->period, $this->fulltext);
		$this->template->reservations = $this->reservationDao->fetch($query)->applyPaginator($this['p']->getPaginator());
	}


	protected function createComponentSearchForm()
	{
		$form = $this->simpleFormFactory->create();

		$rentals = $this->loggedUser->getRentals()->toArray();
		if(count($rentals)) {
			$rentals = \Tools::entitiesMap($rentals, 'id', 'name', $this->translator);
			$form->addSelect('rental', '', $rentals)
				->setPrompt('--!!!vsetny--');
		}


		$form->addSelect('period', '', [
			SearchQuery::PERIOD_PAST => '!!!past',
			SearchQuery::PERIOD_PRESENT => '!!!present',
			SearchQuery::PERIOD_FUTURE => '!!!future',
		])
			->setPrompt('--!!!vsetny--');

		$form->addText('fulltext', '');

		$form->addSubmit('submit', '');

		$form->onAttached[] = function($form) {
			$form['rental']->setDefaultValue($this->rental);
			$form['period']->setDefaultValue($this->period);
			$form['fulltext']->setDefaultValue($this->fulltext);
		};

		$form->onSuccess[] = function($form) {
			$values = $form->getValues();

			$this->rental = $values->rental;
			$this->period = $values->period;
			$this->fulltext = $values->fulltext;

			$this->redirect('this');
		};

		return $form;
	}


	protected function createComponentP() {
		$vp = new \VisualPaginator();
		$vp->getPaginator()->itemsPerPage = 10;
		$vp->templateFile = APP_DIR.'/FrontModule/components/VisualPaginator/paginator.latte';

		return $vp;
	}


}
