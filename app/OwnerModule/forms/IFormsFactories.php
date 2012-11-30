<?php

namespace OwnerModule\Forms;

interface IRentalEditFormFactory {

	public function create(\Entity\Rental\Rental $rental);

}