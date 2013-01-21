<?php

namespace Routers;

use Nette;
use Nette\Application\Routers\Route;
use Nette\Utils\Strings;
use Nette\Utils\Arrays;

class FrontRouteList extends BaseRouteList
{

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
	public function __construct(\Repository\LanguageRepository $languageRepository, \Repository\Location\LocationRepository $locationRepository)
	{
		parent::__construct($languageRepository, $locationRepository, 'Front');

		$this[] = new Route('//<language ([a-z]{2}|www)>.<primaryLocation [a-z]{2,3}>.%domain%/[<hash .*>]', array(
			self::PARAM_PRIMARY_LOCATION => 'sk',
			self::PARAM_LANGUAGE => 'www',
			'presenter' => '*',
			'action' => 'list',
		));

	}


	/**
	 * @param array $params
	 *
	 * @return array
	 */
	public function filterIn(array $params)
	{
		$params = parent::filterIn($params);

		return $params;
	}

	/**
	 * @param array $params
	 *
	 * @return array
	 */
	public function filterOut(array $params)
	{
		$params = parent::filterOut($params);

		return $params;
	}


	/**
	 * @param \Nette\Http\IRequest $httpRequest
	 * @return \Nette\Application\Request|NULL
	 */
	public function match(Nette\Http\IRequest $httpRequest)
	{

		/** @var Route $route */
		$route = $this[0];
		if ($appRequest = $route->match($httpRequest)) {
			$presenter = $appRequest->getPresenterName();

			$params = $appRequest->getParameters();
			$params = $this->filterIn($params);

			$pathSegments = array_filter(explode('/', $params[self::PARAM_HASH]));
			unset($params[self::PARAM_HASH]);

			if(count($pathSegments) == 0) {
				$presenter = 'Home';
				$params['action'] = 'default';
			} else if(count($pathSegments) == 1) {
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


			$appRequest->setPresenterName($this->getModule() . $presenter);
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
		$params = $this->filterOut($params);
		$appRequest->setParameters($params);

		$params[self::PARAM_HASH] = '';

		$url = parent::constructUrl($appRequest, $refUrl);

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


}


interface IFrontRouteListFactory {
	/**
	 * @return FrontRouteList
	 */
	public function create();
}