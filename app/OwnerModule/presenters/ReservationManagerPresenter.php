<?php

namespace OwnerModule;



use Entity\User\RentalReservation;
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
	 * @persistent
	 * @var int
	 */
	public $rental;

	/**
	 * @persistent
	 * @var string
	 */
	public $period = SearchQuery::PERIOD_CURRENT;

	/**
	 * @persistent
	 * @var string
	 */
	public $fulltext;

	/**
	 * @persistent
	 * @var bool
	 */
	public $showCanceled;


	public function injectDependencies(\Nette\DI\Container $dic) {
		$this->reservationDao = $dic->getService('doctrine.default.entityManager')->getDao(RESERVATION_ENTITY);
	}


	public function actionAdd()
	{
		/** @var $reservation \Entity\User\RentalReservation */
		$reservation = $this->reservationDao->createNew();

		$reservation->setRental($this->loggedUser->getFirstRental());

		$this->em->persist($reservation);
		$this->em->flush();

		$this->redirect('ReservationEdit:', ['id' => $reservation->getId()]);
	}

	public function handleChangeStatus($id, $status)
	{
		$reservation = $this->findReservation($id);
		$this->checkPermission($reservation, 'edit');

		# !!! POZOR !!! nemoze dat status 'confirmed' pokial nevybral unit
		if(!in_array($status, [\Entity\User\RentalReservation::STATUS_CANCELED]))
			throw new InvalidArgumentException;

		$reservation->setStatus($status);

		$this->em->flush($reservation);

//		$this->template->editedReservation = $reservation->id;
		$this->invalidateControl('allReservationsWrapper');
	}


	public function actionList()
	{
		if($restoreSearch = $this->getParameter('restoreSearch')) {
			$this->restoreSearch($restoreSearch);
		}
		$this->template->storedSearch = $this->storeSearch();
	}

	public function renderList()
	{
		if($this->rental) {
			$rentals = [$this->findRental($this->rental)];
		} else {
			$rentals = $this->loggedUser->getRentals()->toArray();
		}
		$query = $this->searchFactory->create($rentals, $this->period, $this->fulltext, $this->showCanceled);
		if(isset($this->template->editedReservation)) {
			$query->filterIds([$this->template->editedReservation]);
		}

		$this->template->reservations = $this->reservationDao->fetch($query)->applyPaginator($this['p']->getPaginator());
	}

	protected function storeSearch($expiration = '+ 10 minutes')
	{
		$session = $this->session->getSection('ReservationManager/storedSearch');
		do {
			$key = \Nette\Utils\Strings::random(5);
		} while (isset($session[$key]));

		$session[$key] = array($this->user->getId(), [
			'rental' => $this->rental,
			'period' => $this->period,
			'fulltext' => $this->fulltext,
			'showCanceled' => (bool) $this->showCanceled,
		]);
		$session->setExpiration($expiration, $key);
		return $key;
	}

	public function restoreSearch($key)
	{
		$session = $this->session->getSection('ReservationManager/storedSearch');
		if (!isset($session[$key]) || ($session[$key][0] !== NULL && $session[$key][0] !== $this->user->getId())) {
			return;
		}
		$search = $session[$key][1];
		unset($session[$key]);

		$this->rental = Arrays::get($search, 'rental', NULL);
		$this->period = Arrays::get($search, 'period', NULL);
		$this->fulltext = Arrays::get($search, 'fulltext', NULL);
		$this->showCanceled = (bool) Arrays::get($search, 'showCanceled', NULL);
		$this->redirect('this');
	}



	protected function createComponentSearchForm()
	{
		$form = $this->simpleFormFactory->create();

		$rentals = $this->loggedUser->getRentals()->toArray();
		if (count($rentals)>1) {
			$rentals = \Tools::entitiesMap($rentals, 'id', 'name', $this->translator);
			$form->addSelect('rental', '', $rentals)
				->setPrompt('- ' . $this->translate(721502) . ' -');
		}


		$form->addSelect('period', '', [
			SearchQuery::PERIOD_CURRENT => 'a2',
			SearchQuery::PERIOD_NONE => 'a3',
			SearchQuery::PERIOD_PAST => 720292,
		]);

		$form->addText('fulltext', '');

		$form->addCheckbox('showCanceled', '');

		$form->addSubmit('submit', '');

		$form->onAttached[] = function($form) {
			if (isset($form['rental'])) {
				$form['rental']->setDefaultValue($this->rental);
			}
			$form['period']->setDefaultValue($this->period);
			$form['fulltext']->setDefaultValue($this->fulltext);
			$form['showCanceled']->setDefaultValue($this->showCanceled);
		};

		$form->onSuccess[] = function($form) {
			$values = $form->getValues();

			$this->rental = $values->rental;
			$this->period = $values->period;
			$this->fulltext = $values->fulltext;
			$this->showCanceled = $values->showCanceled;

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

