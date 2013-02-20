<?php

namespace Routers;

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

	const SPOKEN_LANGUAGE = 'flanguage';
	const CAPACITY = 'fcapacity';
	const BOARD = 'fboard';
	const PRICE = 'fprice';

	public static $pathSegmentTypes = array(
		'page' => 2,
		'location' => 6,
		'rentalType' => 8,
	);

	public $locationRepositoryAccessor;
	public $languageRepositoryAccessor;
	public $rentalRepositoryAccessor;
	public $rentalTypeRepositoryAccessor;
	public $routingPathSegmentRepositoryAccessor;
	public $domainRepositoryAccessor;
	public $rentalAmenityRepositoryAccessor;
	public $favoriteListRepositoryAccessor;
	public $pageRepositoryAccessor;
	public $phraseDecoratorFactory;

	/**
	 * @param \Repository\LanguageRepository $languageRepository
	 * @param \Repository\Location\LocationRepository $locationRepository
	 */
	public function __construct(LanguageRepository $languageRepository, LocationRepository $locationRepository)
	{
		$mask = '//[!<language ([a-z]{2}|www)>.<primaryLocation [a-z]{2,3}>.%domain%/][<hash .*>]';
		$metadata = [ 'presenter' => 'Rental', 'action' => 'list' ];
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
			$params = $this->filterIn($params);

			if(isset($params[self::HASH])) {
				$pathSegments = array_filter(explode('/', $params[self::HASH]));
			}
			unset($params[self::HASH]);

			$tmp = $params;
			unset($tmp[self::LANGUAGE], $tmp[self::PRIMARY_LOCATION], $tmp['action']);
			if(!count($tmp) && !count($pathSegments)) {
				$presenter = 'Home';
				$params['action'] = 'default';
			}

			if(count($pathSegments) == 1) {
				$pathSegment = reset($pathSegments);
				if($match = Strings::match($pathSegment, '~\.*-([0-9]+)~')) {
					if($rental = $this->rentalRepositoryAccessor->get()->findOneByOldId($match[1])) {
						$params[self::RENTAL] = $rental;
						$presenter = 'Rental';
						$params['action'] = 'detail';
					}
				} else if ($match = Strings::match($pathSegment, '~\.*-r([0-9]+)~')) {
					if($rental = $this->rentalRepositoryAccessor->get()->find($match[1])) {
						$params[self::RENTAL] = $rental;
						$presenter = 'Rental';
						$params['action'] = 'detail';
					}
				} else if ($match = Strings::match($pathSegment, '~f([0-9]+)~')) {
					if($favoriteList = $this->favoriteListRepositoryAccessor->get()->find($match[1])) {
						$params[self::FAVORITE_LIST] = $favoriteList;
						$presenter = 'Rental';
						$params['action'] = 'list';
					}
				}
			}

			if(count($pathSegments)) {
				$segmentList = $this->getPathSegmentList($pathSegments, $params);
				if(count($segmentList)) {
					if(array_key_exists('page', $segmentList)) {
						$page = $segmentList['page'];
						list( , , $presenter, $params['action']) = array_filter(explode(':', $page->destination));
						$params['page'] = $segmentList['page'];
					} else {
						foreach ($segmentList as $key => $value) {
							$params[$key] = $value;
						}
						$presenter = 'Rental';
						$params['action'] = 'list';
					}
				} else {
					$segmentList = array();
				}
			} else {
				$segmentList = array();
			}

			if(count($segmentList) != count($pathSegments)) {
				// @todo pocet najdenych pathsegmentov je mensi
				// ak nejake chybaju tak ich skus najst v PathSegmentsOld
			}

			//d($params); #@debug
			if(!isset($params['action']) || !isset($presenter)) {
				$presenter = 'Rental';
				$params['action'] = 'list';
				// return NULL;
			}


			$appRequest->setPresenterName($presenter);
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
		$params = $appRequest->getParameters();
		$params[self::HASH] = [];

		$presenter = $appRequest->getPresenterName();
		$action = $params['action'];

		switch (TRUE) {
			case $presenter == 'Home' && $action == 'default':
			case $presenter == 'Rental' && $action == 'detail':
			case $presenter == 'Rental' && $action == 'list':
				unset($params['page']);
		}

		$params = $this->filterOut($params);

		if(!count($params[self::HASH])) {
			$params[self::HASH] = '';
		} else {
			$params[self::HASH] = implode('/', $params[self::HASH]);
		}

		$params['action'] = 'list';
		$appRequest->setPresenterName($this->presenterName);

		$appRequest->setParameters($params);


		$url = $this->route->constructUrl($appRequest, $refUrl);

		if(!$url) {
			return NULL;
		} else {
			return $url;
		}
	}


	public function filterIn(array $params)
	{
		$params = parent::filterIn($params);

		if(isset($params[self::SPOKEN_LANGUAGE])) {
			$params[self::SPOKEN_LANGUAGE] = $this->languageRepository->find($params[self::SPOKEN_LANGUAGE]);
		}

		return $params;
	}

	public function filterOut(array $params)
	{
		$segments = [];

		if(isset($params[self::RENTAL])) {
			$segments[self::RENTAL] = $params[self::RENTAL]->slug . '-r' . $params[self::RENTAL]->id;
			unset($params[self::RENTAL]);
		}

		if(isset($params[self::FAVORITE_LIST])) {
			$segments[self::FAVORITE_LIST] = 'f' . $params[self::FAVORITE_LIST]->id;
			unset($params[self::FAVORITE_LIST]);
		}

		if(isset($params[self::SPOKEN_LANGUAGE])) {
			$params[self::SPOKEN_LANGUAGE] = $params[self::SPOKEN_LANGUAGE]->getId();
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

		$pathSegmentList = $this->routingPathSegmentRepositoryAccessor->get()
			->findForRouter($params['language'], $params['primaryLocation'], $pathSegments);

		foreach ($pathSegmentList as $value) {
			$keyTemp = $pathSegmentTypesFlip[$value->type];
			$accessor = $keyTemp.'RepositoryAccessor';
			$pathSegmentListNew[$keyTemp] = $this->{$accessor}->get()->find($value->entityId);
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

}


interface IFrontRouteFactory {
	/**
	 * @return FrontRoute
	 */
	public function create();
}