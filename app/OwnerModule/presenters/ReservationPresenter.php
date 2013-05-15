<?php

namespace OwnerModule;


use Nette\InvalidArgumentException;

class ReservationPresenter extends BasePresenter {

	public function actionList($rental)
	{
		/** @var $rental \Entity\Rental\Rental */
		$rental = $this->rentalRepositoryAccessor->get()->find($rental);
		if(!$rental) {
			throw new InvalidArgumentException;
		}

		/** @var $reservationRepository \Repository\User\RentalReservationRepository */
		$reservationRepository = $this->userRentalReservationRepositoryAccessor->get();

		$reservationsPerPage = 30;
		$vp = $this['vp'];
		/** @var $paginator \Nette\Utils\Paginator */
		$paginator = $vp->getPaginator();
		$paginator->itemsPerPage = $reservationsPerPage;
		$paginator->itemCount = $reservationRepository->getCountForRental($rental);

		$reservations = $reservationRepository->findForRental($rental, $paginator->itemsPerPage, $paginator->getOffset());

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
