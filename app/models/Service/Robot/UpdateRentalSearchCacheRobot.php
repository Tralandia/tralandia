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
	protected $rentalRepository;

	public function __construct(\Entity\Location\Location $primaryLocation, \Extras\Cache\IRentalSearchCachingFactory $rentalSearchFactory, \Extras\Cache\IRentalOrderCachingFactory $rentalOrderFactory, \Doctrine\ORM\EntityManager $entityManager) {
		$this->rentalSearchFactory = $rentalSearchFactory;
		$this->rentalOrderFactory = $rentalOrderFactory;
		$this->primaryLocation = $primaryLocation;
		$this->rentalRepository = $entityManager->getRepository('\Entity\Rental\Rental');
	}

	public function needToRun() {
		return true;
	}

	public function run() {
		$rentals = $this->rentalRepository->findByPrimaryLocation($this->primaryLocation, \Entity\Rental\Rental::STATUS_LIVE);

		$cache = $this->rentalSearchFactory->create($this->primaryLocation);
		foreach ($rentals as $rental) {
			$cache->addRental($rental);
		}
		$cache->save();

		$cache = $this->rentalOrderFactory->create($this->primaryLocation);
		$cache->reset();
		//d($cache->getOrderList());
		//d($cache->getFeaturedList());
		$cache->save();
	}
}