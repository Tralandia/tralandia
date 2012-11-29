<?php

namespace AdminModule;

use Nette;
use Nette\Utils\Strings;

class DavidPresenter extends BasePresenter {

	private $frontRouteFactory;

	public function injectRoute(\Routers\IFrontRouteFactory $frontRouteFactory) {
		$this->frontRouteFactory = $frontRouteFactory;
	}

	public function actionList() {

		//$this->getService('generatePathSegmentsRobot')->run();

		$variables = Strings::matchAll('Ubytovanie [locationLocative] [amindenit]', '/\[(?P<replacements>[a-zA-Z]+)\]/');
		d($variables); exit;
		$url = 'http://www.sk.tra.com/nitra';
		$url = new Nette\Http\UrlScript($url);
		$httpRequest = new Nette\Http\Request($url);

		$route = $this->frontRouteFactory->create();

		$request = $route->match($httpRequest);
		d($request);
		$languageRepositoryAccessor = $this->getService('languageRepositoryAccessor');
		$locationRepositoryAccessor = $this->getService('locationRepositoryAccessor');

		$seo = new \Service\Seo\SeoService($request, $this->getService('pageRepositoryAccessor'));
	}
}