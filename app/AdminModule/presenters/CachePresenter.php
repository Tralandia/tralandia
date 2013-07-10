<?php

namespace AdminModule;


use Nette\ArrayHash;
use Nette\Caching\Cache;

class CachePresenter extends BasePresenter {

	/**
	 * @autowire
	 * @var \Service\Rental\IRentalSearchServiceFactory
	 */
	protected $rentalSearchServiceFactory;

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
