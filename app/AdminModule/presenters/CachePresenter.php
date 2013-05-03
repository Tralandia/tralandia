<?php

namespace AdminModule;


use Nette\Caching\Cache;

class CachePresenter extends BasePresenter {

	public function actionDashboard()
	{

	}

	public function actionInvalidateTranslatorCache()
	{
		$this->invalidateCache('translatorCache', ['translator']);
	}

	public function actionInvalidateHeaderCache()
	{
		$this->invalidateCache('templateCache', ['header']);
	}

	public function actionInvalidateFooterCache()
	{
		$this->invalidateCache('templateCache', ['footer']);
	}

	public function actionInvalidateSearchBarCache()
	{
		$this->invalidateCache('templateCache', ['searchBar']);
	}

	public function actionInvalidateSearchLinksCache()
	{
		$this->invalidateCache('templateCache', ['searchLinks']);
	}

	public function actionInvalidateHomeCache()
	{
		$this->invalidateCache('templateCache', ['home']);
	}

	public function actionInvalidateRentalBrickCache()
	{
		$this->invalidateCache('templateCache', ['rentalBrick']);
	}

	public function actionInvalidateRentalDetailCache()
	{
		$this->invalidateCache('templateCache', ['rentalDetail']);
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
}
