<?php

namespace AdminModule;

class CibiPresenter extends BasePresenter {

	public $domainRepositoryAccessor;
	public $locationRepositoryAccessor;
	public $rentalTypeRepositoryAccessor;

	public function setContext(\Nette\DI\Container $dic) {
		parent::setContext($dic);

		$this->setProperty('domainRepositoryAccessor');
		$this->setProperty('rentalTypeRepositoryAccessor');
	}

	public function actionList() {
		$searchCaching = $this->getService('searchCaching');

		$country = $this->locationRepositoryAccessor->get()->findBySlug('slovakia');
		$searchCaching->setCountry($country);

		$criteria = array();
		$criteria[] = $searchCaching->getCache($country, SearchCaching::CRITERIA_COUNTRY);
		$criteria[] = new SearchCaching($domain, SearchCaching::CRITERIA_DOMAIN);

		$search->setCriteria($criteria);

	}

}