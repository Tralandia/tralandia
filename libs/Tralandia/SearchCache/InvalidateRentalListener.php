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
use Nette\Caching\Cache;

class InvalidateRentalListener implements \Kdyby\Events\Subscriber {


	/**
	 * @var \Extras\Cache\IRentalSearchCachingFactory
	 */
	private $rentalSearchCachingFactory;

	/**
	 * @var \Nette\Caching\Cache
	 */
	private $templateCache;

	/**
	 * @var \Nette\Caching\Cache
	 */
	private $translatorCache;


	public function __construct(Cache $templateCache, Cache $translatorCache, IRentalSearchCachingFactory $rentalSearchCachingFactory)
	{
		$this->rentalSearchCachingFactory = $rentalSearchCachingFactory;
		$this->templateCache = $templateCache;
		$this->translatorCache = $translatorCache;
	}

	public function getSubscribedEvents()
	{
		return [
			'FormHandler\RegistrationHandler::onSuccess',
			'FormHandler\RentalEditHandler::onSuccess',
		];
	}


	public function onSuccess(Rental $rental)
	{
		$rentalSearchCaching = $this->rentalSearchCachingFactory->create($rental->getPrimaryLocation());
		$rentalSearchCaching->updateRental($rental);

		$this->templateCache->clean([
			Cache::TAGS => ['rental/' . $rental->getId()],
		]);

		$this->translatorCache->remove($rental->getName()->getId());
		$this->translatorCache->remove($rental->getTeaser()->getId());
		foreach($rental->getInterviewAnswers() as $answer) {
			$this->translatorCache->remove($answer->getAnswer()->getId());
		}
	}




}
