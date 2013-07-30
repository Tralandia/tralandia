<?php
/**
 * This file is part of the Tralandia.
 * User: david
 * Created at: 7/24/13 2:11 PM
 */

namespace Tralandia\Rental;


use Doctrine\ORM\EntityManager;
use Entity\Rental\Rental;
use Extras\Cache\IRentalSearchCachingFactory;
use Image\RentalImageManager;
use Nette;

class Discarder {


	/**
	 * @var \Doctrine\ORM\EntityManager
	 */
	private $em;

	/**
	 * @var \Image\RentalImageManager
	 */
	private $imageManager;

	/**
	 * @var \RentalPriceListManager
	 */
	private $pricelistManager;

	/**
	 * @var \Extras\Cache\IRentalSearchCachingFactory
	 */
	private $rentalSearchCachingFactory;


	public function __construct(EntityManager $em, RentalImageManager $imageManager,
								\RentalPriceListManager $pricelistManager, IRentalSearchCachingFactory $rentalSearchCachingFactory)
	{
		$this->em = $em;
		$this->imageManager = $imageManager;
		$this->pricelistManager = $pricelistManager;
		$this->rentalSearchCachingFactory = $rentalSearchCachingFactory;
	}

	public function discard(Rental $rental)
	{
		foreach($rental->getImages() as $image) {
			$this->imageManager->delete($image);
		}

		foreach($rental->getPricelists() as $pricelist) {
			$this->pricelistManager->delete($pricelist);
		}

		$rentalSearchCaching = $this->rentalSearchCachingFactory->create($rental->getPrimaryLocation());
		$rentalSearchCaching->removeRental($rental);

		/** @var $rentalRepository \Repository\Rental\RentalRepository */
		$rentalRepository = $this->em->getRepository(RENTAL_ENTITY);
		$rentalRepository->delete($rental);
	}

}
