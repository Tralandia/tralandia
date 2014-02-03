<?php
/**
 * This file is part of the Tralandia.
 * User: david
 * Created at: 7/22/13 3:35 PM
 */

namespace Tralandia\SearchCache;


use Doctrine\ORM\EntityManager;
use Entity\Rental\Rental;
use Robot\IUpdateRentalSearchCacheRobotFactory;
use Nette;
use Nette\Caching\Cache;

class InvalidateRentalListener implements \Kdyby\Events\Subscriber {


	/**
	 * @autowire
	 * @var \Robot\IUpdateRentalSearchCacheRobotFactory
	 */
	protected $updateRentalSearchCacheRobotFactory;

	/**
	 * @var \Nette\Caching\Cache
	 */
	private $templateCache;

	/**
	 * @var \Nette\Caching\Cache
	 */
	private $translatorCache;

	/**
	 * @var \Nette\Caching\Cache
	 */
	private $mapSearchCache;


	public function __construct(Cache $templateCache, Cache $translatorCache, Cache $mapSearchCache, IUpdateRentalSearchCacheRobotFactory $updateRentalSearchCacheRobotFactory)
	{
		$this->templateCache = $templateCache;
		$this->mapSearchCache = $mapSearchCache;
		$this->translatorCache = $translatorCache;
		$this->updateRentalSearchCacheRobotFactory = $updateRentalSearchCacheRobotFactory;
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
		$this->updateRentalSearchCacheRobotFactory->create($rental->getPrimaryLocation())->runForRental($rental);

		$this->templateCache->clean([
			Cache::TAGS => ['rental/' . $rental->getId()],
		]);

		$this->translatorCache->remove($rental->getName()->getId());
		$this->translatorCache->remove($rental->getTeaser()->getId());
		foreach($rental->getInterviewAnswers() as $answer) {
			$this->translatorCache->remove($answer->getAnswer()->getId());
		}

		$this->mapSearchCache->remove($rental->getId());
	}




}
