<?php

namespace AdminModule;

use Nette;
use Nette\Utils\Strings;

class DavidPresenter extends BasePresenter {

	private $frontRouteFactory;
	private $seoServiceFactory;

	public function injectRoute(\Routers\IFrontRouteFactory $frontRouteFactory, \Service\Seo\ISeoServiceFactory $seoServiceFactory) {
		$this->frontRouteFactory = $frontRouteFactory;
		$this->seoServiceFactory = $seoServiceFactory;
	}

	public function actionList() {

		//$this->getService('generatePathSegmentsRobot')->run();

		$variables = Strings::matchAll('Ubytovanie [locationLocative] [amindenit]', '/\[(?P<replacements>[a-zA-Z]+)\]/');
		// d($variables); exit;
		$url = 'http://www.sk.tra.com/nitra/golf';
		$url = new Nette\Http\UrlScript($url);
		$httpRequest = new Nette\Http\Request($url);

		$route = $this->frontRouteFactory->create();

		$request = $route->match($httpRequest);

		$languageRepositoryAccessor = $this->getService('languageRepositoryAccessor');
		$locationRepositoryAccessor = $this->getService('locationRepositoryAccessor');

		$seo = $this->seoServiceFactory->create($request);
		d($seo->getH1());
		d($seo->getTitle());

	}
}