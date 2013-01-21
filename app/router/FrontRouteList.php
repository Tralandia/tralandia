<?php

namespace Routers;

use Nette;
use Nette\Application\Routers\Route;
use Nette\Utils\Strings;
use Nette\Utils\Arrays;

class FrontRouteList extends BaseRouteList
{

	/**
	 * @var \Nette\Application\Routers\Route
	 */
	protected $route;

	const PARAM_HASH = 'hash';

	protected static $pathSegmentTypes = array(
		'page' => 2,
		'location' => 6,
		'rentalType' => 8,
		'rentalTag' => 10,
	);

	public $locationRepositoryAccessor;
	public $languageRepositoryAccessor;
	public $rentalRepositoryAccessor;
	public $rentalTypeRepositoryAccessor;
	public $routingPathSegmentRepositoryAccessor;
	public $domainRepositoryAccessor;
	public $rentalTagRepositoryAccessor;
	public $rentalAmenityRepositoryAccessor;
	public $pageRepositoryAccessor;
	public $phraseDecoratorFactory;

	/**
	 * @param \Repository\LanguageRepository $languageRepository
	 * @param \Repository\Location\LocationRepository $locationRepository
	 */
	public function __construct(\Repository\LanguageRepository $languageRepository,
							\Repository\Location\LocationRepository $locationRepository)
	{
		parent::__construct($languageRepository, $locationRepository);
		$this->route = new Route('//<language ([a-z]{2}|www)>.<primaryLocation [a-z]{2,3}>.%domain%/<hash .*>', array(
			self::PARAM_PRIMARY_LOCATION => 'sk',
			self::PARAM_LANGUAGE => 'www',
			'presenter' => 'Rental',
			'action' => 'list',
		));
	}


	/**
	 * @param \Nette\Http\IRequest $httpRequest
	 * @return \Nette\Application\Request|NULL
	 */
	public function match(Nette\Http\IRequest $httpRequest)
	{

		$route = $this->route;
		if ($appRequest = $route->match($httpRequest)) {
			$presenter = $appRequest->getPresenterName();

			$params = $appRequest->getParameters();
			$params = $this->filterIn($params);

			$pathSegments = array_filter(explode('/', $params[self::PARAM_HASH]));
			unset($params[self::PARAM_HASH]);

			if(count($pathSegments) == 1) {
				$pathSegment = reset($pathSegments);
				if($match = Strings::match($pathSegment, '~\.*-r([0-9]+)~')) {
					if($rental = $this->rentalRepositoryAccessor->get()->find($match[1])) {
						$params['rental'] = $rental;
						$presenter = 'Rental';
						$params['action'] = 'detail';
					}
				}
			}

			if(count($pathSegments)) {
				$segmentList = $this->getPathSegmentList($pathSegments, $params);
				if(count($segmentList)) {
					if(array_key_exists('page', $segmentList)) {
						$page = $segmentList['page'];
						list( , , $presenter, $params['action']) = array_filter(explode(':', $page->destination));
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
				return NULL;
			}


			$appRequest->setPresenterName($presenter);
			$appRequest->setParameters($params);

			return $appRequest;
		}

		return NULL;
	}


	public function filterOut(array $params)
	{
		$segments = [];
		foreach (static::$pathSegmentTypes as $key => $value) {
			if(!isset($params[$key])) continue;
			$segment = $this->getSegmentById($key, $params);
			if(!$segment) continue;
			$segments[$value] = $segment;
			unset($params[$key]);
		}
		ksort($segments);

		$params[self::PARAM_HASH] = array_merge($params[self::PARAM_HASH], $segments);

		$params = parent::filterOut($params);

		return $params;
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
		$params[self::PARAM_HASH] = [];

		$presenter = $appRequest->getPresenterName();
		$action = $params['action'];

		if($presenter == 'Rental' && $action == 'detail') {
			// hmm
		} else if($presenter == 'Rental' && $action == 'list'){
			// hmm
		} else {
			$destination = ':Front:'.$presenter.':'.$action;
			if($page = $this->pageRepositoryAccessor->get()->findOneByDestination($destination)) {
				$paramsTemp = $params;
				$paramsTemp['page'] = $page->id;
				$params[self::PARAM_HASH][] = $this->getSegmentById('page', $paramsTemp);
			} else {
				return NULL;
			}
		}

		$params = $this->filterOut($params);
		$params[self::PARAM_HASH] = implode('/', $params[self::PARAM_HASH]);
		$appRequest->setParameters($params);

		$url = $this->route->constructUrl($appRequest, $refUrl);

		if(!$url) {
			return NULL;
		} else {
			return $url;
		}
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


interface IFrontRouteListFactory {
	/**
	 * @return FrontRouteList
	 */
	public function create();
}