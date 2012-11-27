<?php

namespace AdminModule;

use Nette;
use Nette\Utils\Strings;

class DavidPresenter extends BasePresenter {


	public function actionList() {

		$url = 'http://www.sk.tra.com/nitra';
		$url = new Nette\Http\UrlScript($url);
		$httpRequest = new Nette\Http\Request($url);

		$route = $this->getService('frontRouteFactory')->create();

		$request = $route->match($httpRequest);

		$languageRepositoryAccessor = $this->getService('languageRepositoryAccessor');
		$locationRepositoryAccessor = $this->getService('locationRepositoryAccessor');
		$environment = new \Extras\Environment(array($request), $languageRepositoryAccessor, $locationRepositoryAccessor);
		d($environment);
		$seo = new \Service\Seo\SeoService($environment, $this->getService('pageRepositoryAccessor'));
	}


}
