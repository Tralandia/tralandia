<?php

namespace Routers;

use Entity\BaseEntity;
use Entity\Page;
use Nette;
use Repository\LanguageRepository;
use Repository\Location\LocationRepository;
use Nette\Application\Routers\Route;
use Nette\Utils\Strings;
use Nette\Utils\Arrays;

class FrontRoute extends BaseRoute
{

	const HASH = 'hash';
	const RENTAL = 'rental';
	const FAVORITE_LIST = 'favoriteList';

	const SPOKEN_LANGUAGE = 'spokenLanguage';
	const CAPACITY = 'capacity';
	const PRICE_FROM = 'priceFrom';
	const PRICE_TO = 'priceTo';
	const BOARD = 'board';
	const PLACEMENT = 'placement';

	const PAGE = 'page';
	const LOCATION = 'location';
	const RENTAL_TYPE = 'rentalType';

	public static $pathSegmentTypes = array(
		self::PAGE => 2,
		self::LOCATION => 6,
		self::RENTAL_TYPE => 8,
	);

	public static $pathParametersMapper = [
		self::LOCATION => 'searchBar-location',
		self::RENTAL_TYPE => 'searchBar-rentalType',
		self::PLACEMENT => 'searchBar-placement',
		self::PRICE_FROM => 'searchBar-priceFrom',
		self::PRICE_TO => 'searchBar-priceTo',
		self::CAPACITY => 'searchBar-capacity',
		self::SPOKEN_LANGUAGE => 'searchBar-spokenLanguage',
		self::BOARD => 'searchBar-board',
	];

	public $urlStampOptions = [
		'action',
		self::PRIMARY_LOCATION,
		self::LANGUAGE,
		self::RENTAL,
		self::RENTAL_TYPE,
		self::LOCATION,
		self::PLACEMENT,
		self::PRICE_FROM,
		self::PRICE_TO,
		self::CAPACITY,
		self::SPOKEN_LANGUAGE,
		self::BOARD,
		self::PAGE,
		self::FAVORITE_LIST,
	];

	public $locationRepositoryAccessor;
	public $languageRepositoryAccessor;
	public $rentalRepositoryAccessor;
	public $rentalTypeRepositoryAccessor;
	public $rentalAmenityRepositoryAccessor;
	public $rentalPlacementRepositoryAccessor;
	public $routingPathSegmentRepositoryAccessor;
	public $routingPathSegmentOldRepositoryAccessor;
	public $domainRepositoryAccessor;
	public $favoriteListRepositoryAccessor;
	public $pageRepositoryAccessor;
	public $phraseDecoratorFactory;

	/**
	 * @var \Device
	 */
	protected $device;

	/**
	 * @var \Nette\Caching\Cache
	 */
	protected $cache;

	/**
	 * @param \Repository\LanguageRepository $languageRepository
	 * @param \Repository\Location\LocationRepository $locationRepository
	 * @param \Device $device
	 */
	public function __construct(LanguageRepository $languageRepository, LocationRepository $locationRepository, \Device $device)
	{
		$this->device = $device;
		$mask = '//[!<language ([a-z]{2}|www)>.<primaryLocation [a-z]{2,4}>.%domain%/][<hash .*>]';
		$metadata = [ 'presenter' => 'RentalList', 'action' => 'default' ];
		parent::__construct($mask, $metadata, $languageRepository, $locationRepository);
	}


	/**
	 * @param \Nette\Http\IRequest $httpRequest
	 * @return \Nette\Application\Request|NULL
	 */
	public function match(Nette\Http\IRequest $httpRequest)
	{

		$route = $this->route;
		if ($appRequest = $route->match($httpRequest)) {
			$presenter = NULL;
			$params['action'] = NULL;
			$pathSegments = [];

			$params = $appRequest->getParameters();
			if(isset($params[self::HASH])) {
				$params[self::HASH] = array_filter(explode('/', $params[self::HASH]));
			}
			$params = $this->filterIn($params);

			$pathSegments = $params[self::HASH];
			unset($params[self::HASH]);

			if(is_array($pathSegments) && 'external/calendar/calendar.php' == join('/',$pathSegments)) {
				if(isset($params['id']) && $rental = $this->rentalRepositoryAccessor->get()->find($params['id'])) {
					$params[self::RENTAL] = $rental;
					$presenter = 'CalendarIframe';
					$params['action'] = 'default';
					unset($params['id']);
				}
			}

			$tmp = $params;
			unset($tmp[self::LANGUAGE], $tmp[self::PRIMARY_LOCATION], $tmp[self::USE_ROOT_DOMAIN], $tmp['action']);
			if(!count($tmp) && !count($pathSegments)) {
				if($params[self::PRIMARY_LOCATION]->getIso() == self::ROOT_DOMAIN) {
					$presenter = 'RootHome';
				} else {
					$presenter = 'Home';
				}
				$params['action'] = 'default';
			}

			unset($params[self::USE_ROOT_DOMAIN]);

			if(count($pathSegments) == 1) {
				$pathSegment = reset($pathSegments);
				if($match = Strings::match($pathSegment, '~\.*-([0-9]+)$~')) {
					if($rental = $this->rentalRepositoryAccessor->get()->findOneByOldId($match[1])) {
						$params[self::RENTAL] = $rental;
						$presenter = 'Rental';
						$params['action'] = 'detail';
					}
				} else if ($match = Strings::match($pathSegment, '~\.*-r([0-9]+)$~')) {
					if($rental = $this->rentalRepositoryAccessor->get()->find($match[1])) {
						/** @var $rental \Entity\Rental\Rental */
						$params[self::RENTAL] = $rental;
						$presenter = 'Rental';
						$params['action'] = 'detail';
						$params[self::PRIMARY_LOCATION] = $rental->getAddress()->getPrimaryLocation();
					}
				} else if ($match = Strings::match($pathSegment, '~f([0-9]*)$~')) {
					if(is_numeric($match[1]) && $favoriteList = $this->favoriteListRepositoryAccessor->get()->find($match[1])) {
						$params[self::FAVORITE_LIST] = $favoriteList;
						$presenter = 'RentalList';
						$params['action'] = 'default';
					} else if($match[1] === '') {
						$presenter = 'RentalList';
						$params['action'] = 'redirectToFavorites';
					}
				}
			}

			if(count($pathSegments)) {
				$segmentList = $this->getPathSegmentList($pathSegments, $params);
				if(count($segmentList)) {
					if(array_key_exists(self::PAGE, $segmentList)) {
						$page = $segmentList[self::PAGE];
						list( , , $presenter, $params['action']) = array_filter(explode(':', $page->destination));
						$params[self::PAGE] = $segmentList[self::PAGE];
					} else {
						foreach ($segmentList as $key => $value) {
							$params[$key] = $value;
						}
						$presenter = 'RentalList';
						$params['action'] = 'default';
					}
				} else {
					$segmentList = array();
				}
			} else {
				$segmentList = array();
			}

			if(isset($params[self::CAPACITY])
				|| isset($params[self::SPOKEN_LANGUAGE])
				|| isset($params[self::BOARD])
				|| isset($params[self::PLACEMENT])
				|| isset($params[self::PRICE_FROM])
				|| isset($params[self::PRICE_TO]))
			{
				$presenter = 'RentalList';
				$params['action'] = 'default';
			}

			if(count($segmentList) != count($pathSegments)) {
				// @todo pocet najdenych pathsegmentov je mensi
				// ak nejake chybaju tak ich skus najst v PathSegmentsOld
			}

			//d($params); #@debug
			if(!isset($params['action']) || !isset($presenter)) {
				$presenter = 'Home';
				$params['action'] = 'default';
				//return NULL;
			}


			$appRequest->setPresenterName($presenter);

			foreach($params as $key => $value) {
				if(array_key_exists($key, self::$pathParametersMapper)) {
					$params[self::$pathParametersMapper[$key]] = $value;
					unset($params[$key]);
				}
			}

			$appRequest->setParameters($params);

			return $appRequest;
		}

		return NULL;
	}


	/**
	 * @param \Nette\Application\Request $appRequest
	 * @param \Nette\Http\Url $refUrl
	 * @return NULL|string
	 */
	public function constructUrl(Nette\Application\Request $appRequest, Nette\Http\Url $refUrl)
	{
		$appRequest = clone $appRequest;


		$urlData = $this->getUrlData($appRequest, $refUrl);
		$url = $urlData['url'];
		$params = $urlData['params'];

		$referenceUrlDomain = $refUrl->getScheme() . '://' . $refUrl->getAuthority() . '/';
		if(!Strings::startsWith($url, $referenceUrlDomain) && $this->device->isSetManually()) {
			$params[self::DEVICE] = $this->device->getDevice();
			$appRequest->setParameters($params);
			$url = $this->route->constructUrl($appRequest, $refUrl);
		}

		if(!$url) {
			return NULL;
		} else {
			return $url;
		}
	}


	/**
	 * @param Nette\Application\Request $appRequest
	 * @param Nette\Http\Url $refUrl
	 *
	 * @return mixed|NULL|string
	 */
	protected function getUrlData(Nette\Application\Request $appRequest, Nette\Http\Url $refUrl)
	{

		$params = $appRequest->getParameters();
		$presenter = $appRequest->getPresenterName();

		if($this->cache) {
			$urlStamp = [
				'presenter' => $presenter,
			];
			foreach($params as $name => $value) {
				$urlStamp[$name] = $value;
				if($urlStamp[$name] instanceof BaseEntity) {
					$urlStamp[$name] = $urlStamp[$name]->getId();
				}
			}
			ksort($urlStamp);

			$urlDataFromCache = $this->cache->load($urlStamp);
			if($urlDataFromCache) {
				return $urlDataFromCache;
			}
		}

		$pathParametersMapper = array_flip(self::$pathParametersMapper);
		foreach($params as $key => $value) {
			if(isset($pathParametersMapper[$key])) {
				if(!isset($params[$pathParametersMapper[$key]])) {
					$params[$pathParametersMapper[$key]] = $value;
				}
				unset($params[$key]);
			}
		}

		$params[self::HASH] = [];

		$action = $params['action'];

		switch (TRUE) {
			case $presenter == 'RentalList' && $action == 'redirectToFavorites':
				$params[self::HASH][] = 'f';
			case $presenter == 'Home' && $action == 'default':
			case $presenter == 'RootHome' && $action == 'default':
			case $presenter == 'Rental' && $action == 'detail':
			case $presenter == 'RentalList' && $action == 'default':
				unset($params[self::PAGE]);
				break;
			default:
				$destination = ':Front:'.$presenter.':'.$action;
				$page = $this->pageRepositoryAccessor->get()->findOneByDestination($destination);
				if($page) {
					$params[self::PAGE] = $page;
				} else {
					return NULL;
				}
		}

		$page = NULL;
		if(isset($params[self::PAGE])) {
			$page = $params[self::PAGE];
		}
		$params = $this->filterOut($params);

		if(isset($params[self::USE_ROOT_DOMAIN])) {
			if(!$page && $params[self::PRIMARY_LOCATION] != self::ROOT_DOMAIN) {
				array_unshift($params[self::HASH], $params[self::PRIMARY_LOCATION]);
			}
			$params[self::PRIMARY_LOCATION] = self::ROOT_DOMAIN;
		}

		if(!count($params[self::HASH])) {
			$params[self::HASH] = '';
		} else {
			if($presenter == 'CalendarIframe') {
				krsort($params[self::HASH]);
			}
			$params[self::HASH] = implode('/', $params[self::HASH]);
		}
		unset($params[self::USE_ROOT_DOMAIN]);

		$params['action'] = $this->actionName;


		$appRequest->setPresenterName($this->presenterName);
		$appRequest->setParameters($params);


		$url = $this->route->constructUrl($appRequest, $refUrl);

		$urlData = [
			'url' => $url,
			'params' => $params,
		];

		if($this->cache) {
			$this->cache->save($urlStamp, $urlData);
		}

		return $urlData;
	}


	public function filterIn(array $params)
	{
		$params = parent::filterIn($params);

		if(isset($params[self::SPOKEN_LANGUAGE])) {
			$params[self::SPOKEN_LANGUAGE] = $this->languageRepository->find($params[self::SPOKEN_LANGUAGE]);
		}

		if(isset($params[self::BOARD])) {
			$params[self::BOARD] = $this->rentalAmenityRepositoryAccessor->get()->find($params[self::BOARD]);
		}

		if(isset($params[self::PLACEMENT])) {
			$params[self::PLACEMENT] = $this->rentalPlacementRepositoryAccessor->get()->find($params[self::PLACEMENT]);
		}

		return $params;
	}

	public function filterOut(array $params)
	{
		$segments = [];

		if(isset($params[self::RENTAL]) && isset($params[self::PAGE]) && ':Front:CalendarIframe:default' == $params[self::PAGE]->getDestination()) {
			$segments[self::RENTAL] = $params[self::RENTAL]->id;
			unset($params[self::RENTAL]);
		}

		if(isset($params[self::RENTAL])) {
			$params[self::PRIMARY_LOCATION] = $params[self::RENTAL]->getAddress()->getPrimaryLocation();
			$segments[self::RENTAL] = $params[self::RENTAL]->getSlug() . '-r' . $params[self::RENTAL]->id;
			unset($params[self::RENTAL]);
		}

		if(isset($params[self::FAVORITE_LIST])) {
			$segments[self::FAVORITE_LIST] = 'f' . $params[self::FAVORITE_LIST]->id;
			unset($params[self::FAVORITE_LIST]);
		}

		if(isset($params[self::SPOKEN_LANGUAGE])) {
			$params[self::SPOKEN_LANGUAGE] = $params[self::SPOKEN_LANGUAGE]->getId();
		}

		if(isset($params[self::BOARD])) {
			$params[self::BOARD] = $params[self::BOARD]->getId();
		}

		if(isset($params[self::PLACEMENT])) {
			$params[self::PLACEMENT] = $params[self::PLACEMENT]->getId();
		}

		if(isset($params[self::PAGE]) && $params[self::PAGE] instanceof Page) {
			$params[self::PAGE] = $params[self::PAGE]->getId();
		}

		foreach (static::$pathSegmentTypes as $key => $value) {
			if(!isset($params[$key])) continue;
			$segment = $this->getSegmentById($key, $params);
			if(!$segment) continue;
			$segments[$value] = $segment;
			unset($params[$key]);
		}
		ksort($segments);

		$params[self::HASH] = array_merge($params[self::HASH], $segments);

		$params = parent::filterOut($params);

		return $params;
	}


	public function getPathSegmentList($pathSegments, $params)
	{
		$pathSegmentListNew = array();

		$pathSegmentTypesFlip = array_flip(static::$pathSegmentTypes);

		$pathSegmentRepository = $this->routingPathSegmentRepositoryAccessor->get();
		$pathSegmentOldRepository = $this->routingPathSegmentOldRepositoryAccessor->get();

		foreach ($pathSegments as $value) {
			$pathSegment = $pathSegmentRepository->findOneForRouter($params['language'], $params['primaryLocation'], $value);
			if(!$pathSegment) {
				$pathSegment = $pathSegmentOldRepository->findOneForRouter($params['language'], $params['primaryLocation'], $value);
				if(!$pathSegment) continue;
			}
			$keyTemp = $pathSegmentTypesFlip[$pathSegment->getType()];
			$accessor = $keyTemp.'RepositoryAccessor';
			$pathSegmentListNew[$keyTemp] = $this->{$accessor}->get()->find($pathSegment->getEntityId());
		}


		return $pathSegmentListNew;
	}


	public function getSegmentById($segmentName, $params)
	{
		$segmentId = $params[$segmentName];
		$language = $params['language'];
		$segment = NULL;

		if($segmentName == 'location') {
			$segmentRow = $this->routingPathSegmentRepositoryAccessor->get()->findOneBy(array(
				'type' => static::$pathSegmentTypes[$segmentName],
				'entityId' => $segmentId
			));
		} else {
			$segmentRow = $this->routingPathSegmentRepositoryAccessor->get()->findOneBy(array(
				'type' => static::$pathSegmentTypes[$segmentName],
				'entityId' => $segmentId,
				'language' => $language
			));
		}

		if($segmentRow) {
			$segment = $segmentRow->pathSegment;
		}

		return $segment;
	}


	/**
	 * @return Nette\Caching\Cache
	 */
	public function getCache()
	{
		return $this->cache;
	}


	/**
	 * @param Nette\Caching\Cache $cache
	 */
	public function setCache(Nette\Caching\Cache $cache)
	{
		$this->cache = $cache;
	}
}


interface IFrontRouteFactory {
	/**
	 * @return FrontRoute
	 */
	public function create();
}
