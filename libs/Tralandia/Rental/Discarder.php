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

	/**
	 * @var BanListManager
	 */
	private $banListManager;


	/**
	 * @param EntityManager $em
	 * @param RentalImageManager $imageManager
	 * @param \RentalPriceListManager $pricelistManager
	 * @param BanListManager $banListManager
	 * @param IRentalSearchCachingFactory $rentalSearchCachingFactory
	 */
	public function __construct(EntityManager $em, RentalImageManager $imageManager,
								\RentalPriceListManager $pricelistManager, BanListManager $banListManager,
								IRentalSearchCachingFactory $rentalSearchCachingFactory)
	{
		$this->em = $em;
		$this->imageManager = $imageManager;
		$this->pricelistManager = $pricelistManager;
		$this->rentalSearchCachingFactory = $rentalSearchCachingFactory;
		$this->banListManager = $banListManager;
	}


	/**
	 * @param Rental $rental
	 */
	public function discard(Rental $rental)
	{

		$this->banListManager->banRental($rental);

		foreach($rental->getImages() as $image) {
			try {
				$this->imageManager->delete($image);
			} catch (Nette\FileNotFoundException $e) {}
		}

		foreach($rental->getPricelists() as $pricelist) {
			try {
				$this->pricelistManager->delete($pricelist);
			} catch (Nette\FileNotFoundException $e) {}
		}

		$rentalSearchCaching = $this->rentalSearchCachingFactory->create($rental->getPrimaryLocation());
		$rentalSearchCaching->deleteRental($rental);

		/** @var $rentalRepository \Repository\Rental\RentalRepository */
		$rentalRepository = $this->em->getRepository(RENTAL_ENTITY);
		$rentalRepository->delete($rental);
	}

}
