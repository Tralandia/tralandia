<?php

namespace OwnerModule;


use Nette\InvalidArgumentException;

class ReservationPresenter extends BasePresenter {

	public function actionList($rental)
	{
		/** @var $rental \Entity\Rental\Rental */
		$rental = $this->rentalDao->find($rental);
		if(!$rental) {
			throw new InvalidArgumentException;
		}

		/** @var $reservationDao \Repository\User\RentalReservationRepository */
		$reservationDao = $this->reservationDao;

		$reservationsPerPage = 30;
		$vp = $this['vp'];
		/** @var $paginator \Nette\Utils\Paginator */
		$paginator = $vp->getPaginator();
		$paginator->itemsPerPage = $reservationsPerPage;
		$paginator->itemCount = $reservationDao->getCountForRental($rental);

		$reservations = $reservationDao->findForRental($rental, $paginator->itemsPerPage, $paginator->getOffset());

		$this->template->today = new \DateTime;
		$this->template->reservations = $reservations;

	}

	public function createComponentVp()
	{
		$vp = new \VisualPaginator();
		$vp->templateFile = APP_DIR.'/FrontModule/components/VisualPaginator/paginator.latte';
		return $vp;
	}

}
