<?php

namespace FrontModule;

use Entity\Location\Location;
use Entity\Rental\Rental;
use Nette;

abstract class BasePresenter extends \BasePresenter {
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
	 * @var \VisitedRentals
	 */
	protected $visitedRentals;

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
	 * @autowire
	 * @var \Tralandia\Rental\Rentals
	 */
	protected $rentals;

	/**
	 * @var \Service\Seo\SeoService
	 */
	public $pageSeo;

	protected function startup() {
		parent::startup();

		$this->pageSeo = $this->seoFactory->create($this->link('//this'), $this->getRequest());
	}


	public function beforeRender()
	{

		$primaryLocation = $this->environment->getPrimaryLocation();

		$this->template->isWorld = $primaryLocation->isWorld();
		$this->template->showSearchBar = !$primaryLocation->isWorld();

		$this->template->currentLanguage = NULL;
		$this->template->currentLocation = NULL;


		$domain = $primaryLocation->getFirstDomain()->getDomain();
		$this->template->domain = 'Tralandia.'. substr($domain, strpos($domain, 'tralandia') + 10);

		$this->template->favoriteRentals = $this->favoriteList->getRentalList();

		if(!isset($this->template->pageH1)) {
			$this->template->pageH1 = $this->pageSeo->getH1();
		}

		$this->template->countryCountObjects =  $this->environment->getPrimaryLocation()->getRentalCount();

		$this->template->homeCacheId = 'home' . $this->environment->getPrimaryLocation()->getId() . '-' .
			$this->environment->getLanguage()->getId();

		$this->template->footerCountriesCacheId = 'footerCountries' . $this->environment->getLanguage()->getId();

		$this->template->currentLanguage = $this->environment->getLanguage();

		$this->template->navigationBarLastActive = $this->getActiveNavbarTab();

		$header = $this->getComponent('head');
		$title = $this->pageSeo->getTitle();
		$title && $header->addTitle($title);


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


	/**
	 * @param Rental $rental
	 *
	 * @return bool
	 */
	public function isRentalFeatured(Rental $rental)
	{
		return $this->rentals->isFeatured($rental);
	}


	/**
	 * @param $id
	 * @param bool $need
	 *
	 * @throws \Exception
	 * @return array
	 */
	public function findRentalData($id, $need = true)
	{
		//d($id);
		if($id instanceof \Entity\Rental\Rental) {
			$rental = $id;
		} else {
			$rental = $this->rentalDao->find($id);

			if(!$rental && $need === false) {
				return null;
			}

			if(!$rental) {
				throw new \Exception('ID: ' . $id);
			}
		}

		$firstInterviewAnswerText = NULL;
		if($rental->hasFirstInterviewAnswer()) {
			$answer = $rental->getFirstInterviewAnswer();
			$answerText = $this->translate($answer->getAnswer());
			if(strlen($answerText) > 2) {
				$firstInterviewAnswerText = $answerText;
			}
		}

		return [
			'entity' => $rental,
			'firstInterviewAnswerText' => $firstInterviewAnswerText,
		];
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
			$rentalRepository = $this->rentalDao;
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

		$suggestLocations['localitiesAndRegions'] = $this->locations->findSuggestForLocalityAndRegion(
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
					$temp = $this->getLocationName($location);
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


	/**
	 * @param Location $location
	 *
	 * @return array
	 */
	public function getLocationName(Location $location)
	{
		$return = [];
		$return['name'] = $this->translate($location->getName());
		if($this->language->getId() !== $this->primaryLocation->getDefaultLanguage()->getId()) {
			$return['nameSource'] = $location->getName()->getSourceTranslationText();
		}
		if(isset($return['nameSource']) && $return['name'] == $return['nameSource']) {
			unset($return['nameSource']);
		}

		return $return;
	}

	protected function generateFavoriteLink()
	{
		$rentals = $this->favoriteList->getRentalList();
		if(count($rentals)) {
			$favoriteListRepository = $this->favoriteListDao;
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

	public function actionUnsubscribe($email)
	{
		if($email) {
			$potentialMemberDao = $this->em->getRepository(POTENTIAL_MEMBER);
			$potentialMember = $potentialMemberDao->findOneBy(['email' => $email]);
			if($potentialMember) {
				$potentialMember->unsubscribed = TRUE;
				$potentialMemberDao->save($potentialMember);

				$this->flashMessage(315392, self::FLASH_ERROR);
			}
		}

		$this->redirect('Home:');
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
