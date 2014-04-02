<?php

namespace OwnerModule;



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
	 * @var BaseDao
	 */
	protected $reservationDao;

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


	public function actionList()
	{
		$rentals = $this->loggedUser->getRentals()->toArray();
		$query = new SearchQuery($rentals);
		$this->template->reservations = $this->reservationDao->fetch($query)->applyPaginator($this['p']->getPaginator());
	}


	protected function createComponentP() {
		$vp = new \VisualPaginator();
		$vp->getPaginator()->itemsPerPage = 10;
		$vp->templateFile = APP_DIR.'/FrontModule/components/VisualPaginator/paginator.latte';

		return $vp;
	}


}
