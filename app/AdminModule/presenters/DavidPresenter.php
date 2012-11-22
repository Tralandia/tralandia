<?php

namespace AdminModule;

use Nette;
use Nette\Utils\Strings;

class DavidPresenter extends BasePresenter {


	public function actionList() {

		$url = 'http://www.tra.sk/nitra';
		$url = new Nette\Http\UrlScript($url);
		$httpRequest = new Nette\Http\Request($url);

		$request = $route->match($httpRequest);

		$languageRepositoryAccessor = $this->getService('languageRepositoryAccessor');
		$locationRepositoryAccessor = $this->getService('locationRepositoryAccessor');
		$environment = new Extras\Environment($request, $languageRepositoryAccessor, $locationRepositoryAccessor);
		d($environment);
		// $seo = new Service\Seo\SeoService;


	}


	

}
