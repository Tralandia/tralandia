<?php

namespace Service\Robot;


/**
 * UpdateRentalSearchCacheRobot class
 *
 * @author Dávid Ďurika
 */
class UpdateRentalSearchCacheRobot extends \Nette\Object implements IRobot {

	protected $rentalSearchFactory;
	protected $rentalOrderFactory;
	protected $primaryLocation;

	public function __construct(\Entity\Location\Location $primaryLocation, \Extras\Cache\IRentalSearchCachingFactory $rentalSearchFactory, \Extras\Cache\IRentalOrderCachingFactory $rentalOrderFactory) {
		$this->rentalSearchFactory = $rentalSearchFactory;
		$this->rentalOrderFactory = $rentalOrderFactory;
		$this->primaryLocation = $primaryLocation;
	}

	public function needToRun() {
		return true;
	}

	public function run() {
		$rentals = $this->primaryLocation->primaryRentals;
		$cache = $this->rentalSearchFactory->create($this->primaryLocation);
		foreach ($rentals as $rental) {
			$cache->addRental($rental);
		}
		$cache->save();

		$cache = $this->rentalOrderFactory->create($this->primaryLocation);
		$cache->reset();
		d($cache->getOrderList());
		d($cache->getFeaturedList());
		$cache->save();
	}
}