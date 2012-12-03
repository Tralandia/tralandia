<?php

namespace Service\Robot;


/**
 * UpdateRentalSearchKeysCacheRobot class
 *
 * @author Dávid Ďurika
 */
class UpdateRentalSearchKeysCacheRobot extends \Nette\Object implements IRobot {

	protected $rentalSearchKeysFactory;
	protected $primaryLocation;

	public function __construct(\Entity\Location\Location $primaryLocation, \Extras\Cache\IRentalSearchKeysCachingFactory $rentalSearchKeysFactory) {
		$this->rentalSearchKeysFactory = $rentalSearchKeysFactory;
		$this->primaryLocation = $primaryLocation;
	}

	public function needToRun() {
		return true;
	}

	public function run() {
		$rentals = $this->primaryLocation->rentals;
		foreach ($rentals as $rental) {
			$this->rentalSearchKeysFactory->create($rental)->updateRentalInCache();
		}
	}

}