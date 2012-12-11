<?php

namespace FrontModule\Forms\Rental;

interface IReservationFormFactory {
	public function create(\Entity\Rental\Rental $rental);

}