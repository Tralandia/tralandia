<?php

namespace Extras\Email\Variables;

/**
 * RentalVariablesFactory class
 *
 * @author Dávid Ďurika
 */
class RentalVariablesFactory {

	protected $rentalServiceFactory;

	public function __construct($rentalServiceFactory) {
		$this->rentalServiceFactory = $rentalServiceFactory;
	}

	public function create(\Entity\Rental\Rental $rental) {
		return new RentalVariables($this->rentalServiceFactory->create($rental));
	}
}