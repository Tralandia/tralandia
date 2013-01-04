<?php

namespace Service\Statistics;

use Doctrine, Entity;

/**
 * @author 
 */
class RentalRegistrations {

	protected $rentalRepository;
	protected $locationRepository;

	public function __construct(\Repository\Rental\RentalRepository $rentalRepository, \Repository\Location\LocationRepository $locationRepository) {

		$this->rentalRepository = $rentalRepository;
		$this->locationRepository = $locationRepository;
	}

	public function getData() {
		$result = $this->rentalRepository->getCounts();
	}
}