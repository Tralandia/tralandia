<?php
/**
 * This file is part of the Tralandia.
 * User: david
 * Created at: 7/22/13 3:35 PM
 */

namespace Tralandia\SearchCache;


use Doctrine\ORM\EntityManager;
use Entity\Rental\Rental;
use Extras\Cache\IRentalSearchCachingFactory;
use Nette;

class InvalidateRentalListener implements \Kdyby\Events\Subscriber {


	/**
	 * @var \Extras\Cache\IRentalSearchCachingFactory
	 */
	private $rentalSearchCachingFactory;


	public function __construct(IRentalSearchCachingFactory $rentalSearchCachingFactory)
	{
		$this->rentalSearchCachingFactory = $rentalSearchCachingFactory;
	}

	public function getSubscribedEvents()
	{
		return [
			'\FormHandler\RentalEditHandler::onSuccess',
		];
	}


	public function onSuccess(Rental $rental)
	{
		$rentalSearchCaching = $this->rentalSearchCachingFactory->create($rental->getPrimaryLocation());
		$rentalSearchCaching->updateRental($rental);
	}




}
