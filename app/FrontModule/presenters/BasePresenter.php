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
	 * @var \Service\Seo\ISeoServiceFactory
	 */
	protected $seoFactory;

	/**
	 * @autowire
	 * @var \FavoriteList
	 */
	protected $favoriteList;

	/**
	 * @autowire
	 * @var \BaseModule\Components\IHeaderControlFactory
	 */
	protected $headerControlFactory;

	/**
	 * @autowire
	 * @var \FrontModule\Components\SearchBar\SearchBarControl
	 */
	protected $searchBarControl;

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

		$this->template->mainMenuItems = $this->rentalTypeRepositoryAccessor->get()->findBy(array(),NULL,8);

		$this->template->localeCode = $this->environment->getLocale()->getCode();

		$primaryLocation = $this->environment->getPrimaryLocation();
		$domain = $primaryLocation->getDomain()->getDomain();
		$this->template->domain = ucfirst($domain);

		$this->template->favoriteRentals = $this->favoriteList->getRentalList();
		$this->template->favoriteVisitedRentals = $this->favoriteList->getVisitedRentals();

		$this->template->pageH1 = $this->pageSeo->getH1();
		$this->template->countryCountObjects =  $this->environment->primaryLocation->rentalCount;

		$this->template->worldwideCount = $this->locationRepositoryAccessor->get()->getWorldwideRentalCount();

		$this->template->homeCacheId = 'home' .
			$this->environment->getPrimaryLocation()->getId() . '-' .
			$this->environment->getLanguage()->getId();

		$this->template->footerCountriesCacheId = 'footerCountries' . $this->environment->getLanguage()->getId();

		$this->template->currentLanguage = $this->environment->getLanguage();

		$header = $this->getComponent('head');
		$header->addTitle($this->pageSeo->getTitle());

		$this->template->og = array();
		$this->template->og['title'] = $this->pageSeo->getH1();
		$this->template->og['type'] = 'hotel';
		$this->template->og['url'] = $this->link('//this');
		if ($this->name == 'Front:Rental' && $this->action == 'detail') {
			$image = $this->template->rental->getMainImage();
			if ($image instanceof \Entity\Rental\Image) {
				$this->template->og['image'] = $this->rentalImagePipe->request($image);
			} else {
				$this->template->og['image'] = $this->rentalImagePipe->requestFake();
			}
		} else {
			$this->template->og['image'] = 'http://www.sk.tra.com/images/logo.png'; //@todo
		}
		$this->template->og['site_name'] = 'Tralandia';

		parent::beforeRender();
	}

	public function createComponentHeader()
	{
		return $this->headerControlFactory->create($this->pageSeo, $this->loggedUser);
	}


	public function actionLocationSuggestion($string)
	{
		if(strlen($string)) {
			$suggestLocations = $this->getSuggestionForLocation($string);
			$this->sendSuggest($suggestLocations);
		}
	}

	public function actionSearchSuggestion($string)
	{
		if(strlen($string)) {
			$suggest = $this->getSuggestionForLocation($string);
			/** @var $rentalRepository \Repository\Rental\RentalRepository */
			$rentalRepository = $this->rentalRepositoryAccessor->get();
			$suggest['rentals'] = $rentalRepository->findSuggestForSearch($string, $this->primaryLocation);
			if(is_numeric($string)) {
				$suggest['rentalId'] = $rentalRepository->find($string);
			}
			$this->sendSuggest($suggest);
		}
	}

	public function getActiveNavbarTab()
	{
		$tabsShow = array(
			'Rental:detail' => array('navBarSerchResults', 'navBarFavorites', 'navBarLastSeen'),
			'RentalList:default' => array('navBarFavorites', 'navBarLastSeen'),
			'Home:default' => array('navBarFavorites', 'navBarLastSeen')
		);

		$request = $this->getHttpRequest();
		$cookies = $request->getCookies();
		
		foreach ($tabsShow as $presenter => $tabs) {
			if (!$this->isLinkCurrent($presenter)) continue;
			if (!isset($cookies['navBarActive'])) break;
			return (in_array($cookies['navBarActive'], $tabs) ? $cookies['navBarActive'] : current($tabs));
		}

		return 'navBarFavorites';
	}

	protected function getSuggestionForLocation($string)
	{
		$suggestLocations = [];
		/** @var $locationRepository \Repository\Location\LocationRepository */
		$locationRepository = $this->locationRepositoryAccessor->get();
		//$suggestLocations['counties'] = $locationRepository->findSuggestForCountries($string);

		$suggestLocations['localitiesAndRegions'] = $locationRepository->findSuggestForLocalityAndRegion(
			$string,
			$this->primaryLocation
		);
		return $suggestLocations;
	}

	public function sendSuggest($data)
	{
		$json = [];
		foreach($data as $groupName => $group) {
			if($groupName == 'rentalId') {
				$json[$groupName] = [
					'id' => $group->getId(),
					'name' => $group->getName()->getTranslationText($this->language)
				];
			} else if($groupName == 'rentals') {
				$json[$groupName] = $group;
			} else {
				/** @var $location \Entity\Location\Location */
				foreach($group as $location) {
					$temp = [];
					$temp['name'] = $location->getName()->getTranslationText($this->language);
					$temp['slug'] = $location->getSlug();
					if($location->isPrimary()) {
						$temp['icon'] = '/images/flags/' . $location->getFlagName();
					}
					$json[$groupName][] = $temp;
				}
			}
		}
		$this->sendJson($json);
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

	public function createComponentSearchBar() {
		return $this->searchBarControl;
	}

}
