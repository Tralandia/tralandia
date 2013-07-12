<?php

namespace AdminModule;


use Nette\ArrayHash;
use Nette\Caching\Cache;
use Service\Rental\RentalSearchService;

class CachePresenter extends BasePresenter {

	/**
	 * @autowire
	 * @var \Service\Rental\IRentalSearchServiceFactory
	 */
	protected $rentalSearchServiceFactory;

	/**
	 * @autowire
	 * @var \Extras\Cache\IRentalSearchCachingFactory
	 */
	protected $rentalSearchCachingFactory;

	/**
	 * @autowire
	 * @var \Robot\IUpdateRentalSearchCacheRobotFactory
	 */
	protected $rentalSearchCacheRobotFactory;

	public function actionDashboard()
	{
		$this->template->languages = $this->em->getRepository(LANGUAGE_ENTITY)->findSupported();
	}

	public function actionInvalidatePhraseCache($id)
	{
		$this->invalidatePhrasesCache([$id]);
		$this->sendPayload();
	}

	public function actionInvalidateCache($id)
	{
		$this->invalidateCache('templateCache', [$id]);
	}

	protected function invalidateCache($serviceName, $tags)
	{
		/** @var $cache \Nette\Caching\Cache */
		$cache = $this->getContext()->getService($serviceName);
		$cache->clean([
			Cache::TAGS => $tags
		]);

		$this->flashMessage('Done');
		$this->redirect('dashboard');

	}


	public function actionViewRentalSearchCache($id)
	{
		$rental = $this->findRental($id);
		$searchCaching = $this->rentalSearchCachingFactory->create($rental->getPrimaryLocation());
		$info = $i = $searchCaching->getRentalCacheInfo($rental);

		$searchCaching->regenerate();

		$info[RentalSearchService::CRITERIA_LOCATION] = $this->em->getRepository(LOCATION_ENTITY)->findById($info[RentalSearchService::CRITERIA_LOCATION]);
		$info[RentalSearchService::CRITERIA_RENTAL_TYPE] = $this->em->getRepository(RENTAL_TYPE_ENTITY)->findById($info[RentalSearchService::CRITERIA_RENTAL_TYPE]);
		$info[RentalSearchService::CRITERIA_SPOKEN_LANGUAGE] = $this->em->getRepository(LANGUAGE_ENTITY)->findById($info[RentalSearchService::CRITERIA_SPOKEN_LANGUAGE]);
		$info[RentalSearchService::CRITERIA_BOARD] = $this->em->getRepository(RENTAL_AMENITY_ENTITY)->findById($info[RentalSearchService::CRITERIA_BOARD]);
		$info[RentalSearchService::CRITERIA_CAPACITY] = implode('; ', $info[RentalSearchService::CRITERIA_CAPACITY]);
		$info[RentalSearchService::CRITERIA_PRICE] = implode('; ', $info[RentalSearchService::CRITERIA_PRICE]);

		$this->template->info = $info;
	}


	public function actionInvalidateRentalSearchCache($id)
	{
		$rental = $this->findRental($id);

		$this->rentalSearchCacheRobotFactory->create($rental->getPrimaryLocation())->runForRental($rental);

		$this->payload->success = TRUE;
		$this->sendPayload();
	}


	public function actionSearchCache()
	{
		$primaryLocations = $this->em->getRepository(LOCATION_ENTITY)->findCountriesWithRentals();

		$cachesInfo = new ArrayHash;
		foreach($primaryLocations as $location) {
			//$cache = $this->rentalSearchServiceFactory->create($location);
			$cachesInfo[$location->getId()] = new ArrayHash;
			$cachesInfo[$location->getId()]['location'] = $location;
			//$cachesInfo[$location->getId()]['cache'] = $cache;
		}

		$this->template->cachesInfo = $cachesInfo;
	}
}
