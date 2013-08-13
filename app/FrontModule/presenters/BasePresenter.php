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
	 * @var \LastSeen
	 */
	protected $lastSeen;

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
	 * @autowire
	 * @var \FrontModule\Components\RootCountries\RootCountriesControl
	 */
	protected $rootCountriesControl;

	/**
	 * @autowire
	 * @var \ShareLinks
	 */
	protected $shareLinks;

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
		$primaryLocation = $this->environment->getPrimaryLocation();

		$this->template->isWorld = $primaryLocation->isWorld();
		$this->template->showSearchBar = !$primaryLocation->isWorld();

		$this->template->currentLanguage = NULL;
		$this->template->currentLocation = NULL;

		$this->template->localeCode = $this->environment->getLocale()->getCode();

		$domain = $primaryLocation->getFirstDomain()->getDomain();
		$this->template->domain = 'Tralandia.'. substr($domain, strpos($domain, 'tralandia') + 10);

		$this->template->favoriteRentals = $this->favoriteList->getRentalList();

		if(!isset($this->template->pageH1)) {
			$this->template->pageH1 = $this->pageSeo->getH1();
		}

		$this->template->countryCountObjects =  $this->environment->getPrimaryLocation()->getRentalCount();

		$this->template->worldwideCount = $this->locationRepositoryAccessor->get()->getWorldwideRentalCount();

		$this->template->homeCacheId = 'home' . $this->environment->getPrimaryLocation()->getId() . '-' .
			$this->environment->getLanguage()->getId();

		$this->template->footerCountriesCacheId = 'footerCountries' . $this->environment->getLanguage()->getId();

		$this->template->currentLanguage = $this->environment->getLanguage();

		$this->template->navigationBarLastActive = $this->getActiveNavbarTab();
		$this->template->seenRentals = $this->lastSeen->getSeen();
		$this->template->lastSeenRentals = $this->lastSeen->getSeenRentals(12);

		$header = $this->getComponent('head');
		$header->addTitle($this->pageSeo->getTitle());


		$this->template->og = array();
		$this->template->og['title'] = $this->pageSeo->getH1();
		$this->template->og['type'] = 'hotel';
		$this->template->og['url'] = $this->link('//this');
		if ($this->name == 'Front:Rental' && $this->action == 'detail') {
			$image = $this->template->rental->getMainImage();
			$this->template->og['image'] = $this->rentalImagePipe->request($image);
		} else {
			$this->template->og['image'] = 'http://www.tralandiastatic.com/images/logo.png'; //@todo
		}
		$this->template->og['site_name'] = 'Tralandia';

		$this->template->companyName = 'Tralandia, s.r.o.';
		$this->template->siteName = ucfirst($domain);


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
			'Rental:detail' => array('navigationBarSearchResults', 'navigationBarFavorites', 'navigationBarLastSeen'),
			'RentalList:default' => array('navigationBarFavorites', 'navigationBarLastSeen'),
			'Home:default' => array('navigationBarFavorites', 'navigationBarLastSeen')
		);

		$request = $this->getHttpRequest();
		$cookies = $request->getCookies();

		foreach ($tabsShow as $presenter => $tabs) {
			if (!$this->isLinkCurrent($presenter)) continue;
			if (!isset($cookies['navigationBarActive'])) break;
			return (in_array($cookies['navigationBarActive'], $tabs) ? $cookies['navigationBarActive'] : current($tabs));
		}

		return 'navigationBarFavorites';
	}

	protected function getSuggestionForLocation($string)
	{
		$suggestLocations = [];
		/** @var $locationRepository \Repository\Location\LocationRepository */
		$locationRepository = $this->locationRepositoryAccessor->get();
		//$suggestLocations['counties'] = $locationRepository->findSuggestForCountries($string);

		$suggestLocations['localitiesAndRegions'] = $locationRepository->findSuggestForLocalityAndRegion(
			$string,
			$this->primaryLocation,
			$this->language
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
					'name' => $this->translate($group->getName())
				];
			} else if($groupName == 'rentals') {
				$json[$groupName] = $group;
			} else {
				/** @var $location \Entity\Location\Location */
				foreach($group as $location) {
					$temp = [];
					$temp['name'] = $this->translate($location->getName());
					if($this->language->getId() !== $this->primaryLocation->getDefaultLanguage()->getId()) {
						$temp['nameSource'] = $location->getName()->getSourceTranslationText();
					}
					if(isset($temp['nameSource']) && $temp['name'] == $temp['nameSource']) {
						unset($temp['nameSource']);
					}
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

	protected function generateFavoriteLink()
	{
		$rentals = $this->favoriteList->getRentalList();
		if(count($rentals)) {
			$favoriteListRepository = $this->favoriteListRepositoryAccessor->get();
			/** @var $favoriteList \Entity\FavoriteList */
			$favoriteList = $favoriteListRepository->createNew();
			$favoriteList->addRentals($rentals);
			$favoriteListRepository->save($favoriteList);

			$shareLink = $this->link('//RentalList:default', ['favoriteList' => $favoriteList]);

			return $shareLink;
		} else {
			return NULL;
		}
	}

	public function actionGenerateFavoriteLink()
	{
		$json = [];
		$shareLink = $this->generateFavoriteLink();
		if($shareLink) {
			$shareText = '#favorites';
			//$shareImage = $this->rentalImagePipe->request($rental->getMainImage());
			$shareImage = '';
			$shareLinks = $this->shareLinks;
			$json['twitterShare'] = (string) $shareLinks->getTwitterShareTag($shareLink, $shareText);
			$json['googlePlusShare'] = (string) $shareLinks->getGooglePlusShareTag($shareLink);
			$json['facebookShare'] = (string) $shareLinks->getFacebookShareTag($shareLink, $shareText);
			$json['pinterestShare'] = (string) $shareLinks->getPinterestShareTag($shareLink, $shareText, $shareImage);
			$json['linkToShare'] = $shareLink;
		}
		$this->sendJson($json);
	}

	public function createComponentFooter($name) {
		return $this->getService('footerControlFactory')->create($this->environment->getPrimaryLocation());
	}

	public function createComponentSearchBar() {
		return $this->searchBarControl;
	}

	public function createComponentRootCountries() {
		return $this->rootCountriesControl;
	}

}
