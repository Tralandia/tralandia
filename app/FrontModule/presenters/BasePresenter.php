<?php

namespace FrontModule;

use Nette;

abstract class BasePresenter extends \BasePresenter {
	/**
	 * @var \Extras\Models\Repository\RepositoryAccessor
	 */
	protected $rentalTypeRepositoryAccessor;

	/**
	 * @var \Extras\Models\Repository\RepositoryAccessor
	 */
	protected $rentalRepositoryAccessor;

	protected $favoriteListRepositoryAccessor;

	/**
	 * @autowire
	 * @var \Extras\Environment
	 */
	protected $environment;

	/**
	 * @autowire
	 * @var \Service\Seo\ISeoServiceFactory
	 */
	protected $seoFactory;

	/**
	 * @autowire
	 * @var \FavoriteList
	 */
	protected $favoriteList;


	/**
	 * @var \Service\Seo\SeoService
	 */
	public $pageSeo;

	public function injectBaseRepositories(\Nette\DI\Container $dic) {
		$this->rentalTypeRepositoryAccessor = $dic->rentalTypeRepositoryAccessor;
		$this->rentalRepositoryAccessor = $dic->rentalRepositoryAccessor;
		$this->favoriteListRepositoryAccessor = $dic->favoriteListRepositoryAccessor;
	}

	protected function startup() {
		parent::startup();

		$this->pageSeo = $this->seoFactory->create($this->link('//this'), $this->getRequest());
	}


	public function beforeRender() {
		$this->template->currentLanguage = NULL;
		$this->template->currentLocation = NULL;

		// $this->template->supportedLanguages = $this->languageRepositoryAccessor->findBySupported(\Entity\Language::SUPPORTED);
		// $this->template->launchedCountries = $this->locationRepositoryAccessor->findBy(array('status'=>\Entity\Location\Location::STATUS_LAUNCHED), null, 15);
		// $this->template->liveRentalsCount = count($this->rentalRepositoryAccessor->findByStatus(\Entity\Rental\Rental::STATUS_LIVE));

		
		$this->template->mainMenuItems = $this->rentalTypeRepositoryAccessor->get()->findBy(array(),NULL,8);

		$this->template->slogan = $this->translate('o21083').' '.$this->translate($this->environment->getPrimaryLocation()->getName() , NULL, array('case' => \Entity\Language::LOCATIVE));


		$language =$this->environment->getLanguage();
		$primaryLocation = $this->environment->getPrimaryLocation();
		$domain = $primaryLocation->getDomain()->getDomain();
		$this->template->envLanguage = $language;
		$this->template->envPrimaryLocation = $primaryLocation;
		$this->template->localeCode = $language->getIso() . '_' . strtoupper($primaryLocation->getIso());
		$this->template->domain = ucfirst($domain);
		$this->template->domainHost = ucfirst(strstr($domain, '.', TRUE));
		$this->template->domainExtension = strstr($domain, '.');

		$supportedLanguages = $this->languageRepositoryAccessor->get()->getSupportedSortedByName();
		$this->template->supportedLanguages = array_chunk($supportedLanguages,round(count($supportedLanguages)/3));

		$this->template->favoriteRentals = $this->favoriteList->getRentalList();
		$this->template->favoriteVisitedRentals = $this->favoriteList->getVisitedRentals();

		$this->template->pageH1 = $this->pageSeo->getH1();
		$this->template->homepageUrl = $this->link('//Home:');
		$this->template->pageTitle = $this->pageSeo->getTitle();
		$this->template->countryCountObjects =  $this->environment->primaryLocation->rentalCount;
		//d($this->environment);

		$this->template->worldwideCount = $this->locationRepositoryAccessor->get()->getWorldwideRentalCount();

		$this->template->homeCacheId = 'home'.$this->template->envPrimaryLocation->id.'-'.$this->template->envLanguage->id;
		$this->template->footerCountriesCacheId = 'footerCountries'.$this->template->envLanguage->id;

		$header = $this->getComponent('header');
		$header->addTitle($this->pageSeo->getTitle());


		
		parent::beforeRender();
	}


	public function actionSearchSuggestion($string)
	{
		if(strlen($string)) {
			$locationRepository = $this->locationRepositoryAccessor->get();
			$suggestLocations['counties'] = $locationRepository->findSuggestForCountries($string);
			$suggestLocations['other'] = $locationRepository->findSuggestForLocalityAndRegion($string,
				$this->primaryLocation);
			$return = [];
			/** @var $location \Entity\Location\Location */
			foreach($suggestLocations as $groupName => $group) {
				foreach($group as $location) {
					$return[$groupName][] = [
						'flag' => $location->getIso() ? '/images/flags/' . $location->getIso() . '.gif' : NULL,
						'name' => $location->getName()->getTranslationText($this->language),
					];
				}
			}
			$this->sendJson($return);
		}
	}

	public function actionGenerateFavoriteLink()
	{
		$json = [];
		$rentals = $this->favoriteList->getRentalList();
		if(count($rentals)) {
			$favoriteListRepository = $this->favoriteListRepositoryAccessor->get();
			/** @var $favoriteList \Entity\FavoriteList */
			$favoriteList = $favoriteListRepository->createNew();
			$favoriteList->addRentals($rentals);
			$favoriteListRepository->save($favoriteList);

			$json['link'] = $this->link('//Rental:list', ['favoriteList' => $favoriteList]);
		}
		$this->sendJson($json);
	}


	public function createComponentBreadcrumb($name) {
		
//		return new \FrontModule\Breadcrumb\Breadcrumb($this, $name);
	}

	public function createComponentFooter($name) {
		return $this->getService('footerControlFactory')->create($this->environment->getPrimaryLocation());
	}

	public function createComponentCountriesFooter($name) {
		return $this->getService('countriesFooterControlFactory')->create();
	}

	public function createComponentSearchBar($name) {
		return $this->getService('searchBarControlFactory')->create($this->environment->getPrimaryLocation());
	}

}
