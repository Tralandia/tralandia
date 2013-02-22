<?php

namespace OwnerModule;


use Nette\InvalidArgumentException;

class ReservationPresenter extends BasePresenter {

	protected $userRentalReservationRepositoryAccessor;

	public function injectRepositories(\Nette\DI\Container $dic) {
		$this->userRentalReservationRepositoryAccessor = $dic->userRentalReservationRepositoryAccessor;
	}


	public function actionList($rental)
	{
		/** @var $rental \Entity\Rental\Rental */
		$rental = $this->rentalRepositoryAccessor->get()->find($rental);
		if(!$rental) {
			throw new InvalidArgumentException;
		}

		$reservations = $this->userRentalReservationRepositoryAccessor->get()->findByRental($rental);

		d($reservations);

		$this->template->reservations = $reservations;

	}

}
