<?php

namespace OwnerModule;



class ReservationManagerPresenter extends BasePresenter
{

	public function actionAdd()
	{
		$reservationDao = $this->em->getRepository(RESERVATION_ENTITY);

		/** @var $reservation \Entity\User\RentalReservation */
		$reservation = $reservationDao->createNew();

		$this->em->persist($reservation);
		$this->em->flush();

		$this->redirect('ReservationEdit:', ['id' => $reservation->getId()]);
	}

}
