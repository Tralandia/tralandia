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

		$reservations = $this->userRentalReservationRepositoryAccessor->get()->findByRental($rental);

		$this->template->reservations = $reservations;

	}

}
