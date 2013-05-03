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

	public function actionInvalidateSearchBarCache()
	{
		$this->invalidateCache('templateCache', ['searchBar']);
	}

	public function actionInvalidateHomeCache()
	{
		$this->invalidateCache('templateCache', ['home']);
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
