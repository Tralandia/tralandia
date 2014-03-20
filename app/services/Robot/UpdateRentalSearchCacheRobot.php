<?php

namespace Robot;

use Doctrine\ORM\EntityManager;
use Entity\Location\Location;
use Entity\Rental\Rental;
use Extras\Cache\IRentalOrderCachingFactory;
use Extras\Cache\IRentalSearchCachingFactory;
use Nette\Utils\Strings;

/**
 * UpdateRentalSearchCacheRobot class
 *
 * @author Dávid Ďurika
 */
class UpdateRentalSearchCacheRobot extends \Nette\Object implements IRobot {

	protected $rentalSearchCachingFactory;
	protected $rentalOrderFactory;
	protected $primaryLocation;
	protected $rentalRepository;

	public function __construct(Location $primaryLocation, IRentalSearchCachingFactory $rentalSearchCachingFactory,
								IRentalOrderCachingFactory $rentalOrderFactory, EntityManager $entityManager) {
		$this->rentalSearchCachingFactory = $rentalSearchCachingFactory;
		$this->rentalOrderFactory = $rentalOrderFactory;
		$this->primaryLocation = $primaryLocation;
		$this->rentalRepository = $entityManager->getRepository('\Entity\Rental\Rental');
	}

	public function needToRun() {
		return TRUE;
	}

	public function run() {
		$cache = $this->rentalSearchCachingFactory->create($this->primaryLocation);
		$cache->updateWholeCache();

		$this->updateOrderCache();

	}

	public function runForRental(Rental $rental)
	{
		$cache = $this->rentalSearchCachingFactory->create($this->primaryLocation);

		$cache->addRental($rental);

		$this->updateOrderCache();
	}


	private function updateOrderCache()
	{
		$cache = $this->rentalOrderFactory->create($this->primaryLocation);
		$cache->reset();
//		d($cache->getOrderList());
//		d($cache->getFeaturedList());
		$cache->save();
	}
}

interface IUpdateRentalSearchCacheRobotFactory {
	/**
	 * @param Location $location
	 *
	 * @return UpdateRentalSearchCacheRobot
	 */
	function create(Location $location);
}
