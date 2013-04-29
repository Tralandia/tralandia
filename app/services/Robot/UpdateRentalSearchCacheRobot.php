<?php

namespace Robot;

use Nette\Utils\Strings;

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
		return TRUE;
	}

	public function run() {
		$rentals = $this->rentalRepository->findByPrimaryLocation($this->primaryLocation, \Entity\Rental\Rental::STATUS_LIVE);

		$cache = $this->rentalSearchFactory->create($this->primaryLocation);
		if(count($rentals)) {
			d(Strings::upper($this->primaryLocation->getIso()).': '.count($rentals) . ' objektov');
		}

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

interface IUpdateRentalSearchCacheRobotFactory {
	/**
	 * @param \Entity\Location\Location $location
	 *
	 * @return UpdateRentalSearchCacheRobot
	 */
	function create(\Entity\Location\Location $location);
}
