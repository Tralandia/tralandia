<?php

namespace FrontModule;

use FrontModule\Forms\Rental\IReservationFormFactory;
use Nette\Utils\Html;
use Nette\Utils\Strings;

/**
 * @persistent(searchBar)
 */
class RentalListPresenter extends BasePresenter {

	/**
	 * @var array
	 */
	public $onSendFavoriteList = [];

	/**
	 * @autowire
	 * @var \SearchHistory
	 */
	protected $searchHistory;

	/**
	 * @autowire
	 * @var \Tralandia\GpsSearchLog\GpsSearchLogManager
	 */
	protected $gpsSearchLogManager;

	/**
	 * @autowire
	 * @var \User\FindOrCreateUser
	 */
	protected $findOrCreateUser;

	public function actionDefault($favoriteList, $email, $getDataForBreadcrumb, $lastSeen)
	{
		if($this->device->isMobile()) {
			$this->setLayout("layoutMobile");
			$this->setView('mobileDefault');
		}

		if($lastSeen) {
			$this->template->pageH1 = $this->translate('456408');

			$rentals = $this->visitedRentals->getSeen();
			$this->prepareListTemplate($rentals, count($rentals));
		} else if($favoriteList) {
			if(isset($email)) {
				$receiver = $this->findOrCreateUser->getUser($email, $this->environment);
				$this->onSendFavoriteList($favoriteList, $receiver);
				$this->sendJson(['success' => TRUE]);
			}

			$rentals = $favoriteList->getRentals();
			$itemCount = $rentals->count();
			$this->template->pageH1 = $this->translate('1219');
			$this->prepareListTemplate($rentals, $itemCount);
		} else if ($getDataForBreadcrumb) {
			$this->getDataForBreadcrumb();
		} else {
			/** @var $search \Service\Rental\RentalSearchService */
			$search = $this['searchBar']->getSearch();

			$itemCount = $search->getRentalsCount();

			if(!$this->isAjax()) {
				$lastSearch = $this->searchHistory;
				$latitude = $this['searchBar']->latitude;
				$longitude = $this['searchBar']->longitude;

				if($latitude && $longitude) {
					if(!$lastSearch->hasGps($latitude, $longitude)) {
						$gpsSearchLog = $this->gpsSearchLogManager->log($latitude, $longitude, $this->getParameter('formatted_address', ''), $this->primaryLocation);
					} else {
						$gpsSearchLog = $this->gpsSearchLogManager->findOneByGps($latitude, $longitude);
					}

					$this->template->gpsSearchLog = $gpsSearchLog;
					$this->template->pageH1 = $gpsSearchLog->text;
				}
				$lastSearch->addSearch($search->getCriteriaData(), $search->getRentalsIds(NULL), $this->pageSeo->getUrl(), $this->pageSeo->getH1());

			}
			$this->prepareListTemplate($search, $itemCount);
		}

		$this->template->latitude = $this['searchBar']->latitude;
		$this->template->longitude = $this['searchBar']->longitude;
		$this->template->getDistance = $this->getDistance;


	}


	public function getDistance($lat, $lng, $olat, $olng)
	{
		$val2 = [$lat, $lng];
		$objectData = ['latitude' => $olat, 'longitude' => $olng];
		return (((acos(sin(($val2[0]*pi()/180))*sin(($objectData['latitude']*pi()/180))+cos(($val2[0]*pi()/180))*cos(($objectData['latitude']*pi()/180))*cos((($val2[1]-$objectData['longitude'])*pi()/180))))*180/pi())*60*1.1515*1.609344*1000);
	}

	public function prepareListTemplate($rentals, $rentalCount)
	{
		$vp = $this['p'];
		/** @var $paginator \Nette\Utils\Paginator */
		$paginator = $vp->getPaginator();
		$paginator->itemsPerPage = \Service\Rental\RentalSearchService::COUNT_PER_PAGE;
		$paginator->itemCount = $rentalCount;


		/** @var $head \HeaderControl */
		$head = $this['head'];
		if(!$paginator->isFirst()) {
			if(($paginator->getPage() - 1) == 1) {
				$prevLink = $this->link('//this', ['p-p' => NULL]);
			} else {
				$prevLink = $this->link('//this', ['p-p' => $paginator->getPage() - 1]);
			}
			$prevTag = Html::el('meta')->rel('prev')->href($prevLink);
			$head->addTag($prevTag);
		}

		if(!$paginator->isLast()) {
			$nextLink = $this->link('//this', ['p-p' => $paginator->getPage() + 1]);
			$nextTag = Html::el('meta')->rel('next')->href($nextLink);
			$head->addTag($nextTag);
		}



		$this->template->totalResultsCount = $paginator->itemCount;

		if($rentals instanceof \Service\Rental\RentalSearchService) {
			$rentals = $rentals->getRentalsIds($paginator->getPage());
		}

		$this->template->language = $this->language;
		$this->template->rentals = $rentals;
		$this->template->findRental = $this->findRentalData;
		$this->template->paginatorPage = $paginator->getPage();
		$this->template->isRentalFeatured = $this->isRentalFeatured;

	}


	public function getDataForBreadcrumb()
	{
		/** @var $search \Service\Rental\RentalSearchService */
		$search = $this['searchBar']->getSearch();
		$rentals = $search->getRentals();

		$json = [];

		foreach($rentals as $rental) {
			$row = [];
			$row['id'] = $rental->getId();
			$row['name'] = $this->translate($rental->getName());
			$row['url'] = $this->link('Rental:detail', ['rental' => $rental]);
			$json[] = $row;
		}

		$this->payload->listData = $json;
		$this->sendPayload();
	}


	public function actionRedirectToFavorites()
	{
		$link = $this->generateFavoriteLink();
		if($link) {
			$this->redirectUrl($link);
		} else {
			$this->redirect('Home:');
		}
	}


	protected function createComponentP() {
		$vp = new \VisualPaginator();
		$vp->templateFile = APP_DIR.'/FrontModule/components/VisualPaginator/paginator.latte';

		return $vp;
	}
}
