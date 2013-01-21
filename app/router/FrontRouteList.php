<?php

namespace Routers;

use Nette;
use Nette\Application\Routers\Route;

class FrontRouteList extends BaseRouteList
{

	const PARAM_HASH = 'hash';

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
			'presenter' => 'Rental',
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
/*
			$pathSegments = explode('/', $params[self::PARAM_HASH]);

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
*/


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
		$params = $oldParameters = $appRequest->getParameters();
		$params = $this->filterOut($params);
		$appRequest->setParameters($params);
		$url = parent::constructUrl($appRequest, $refUrl);

		if(!$url) {
			$appRequest->setParameters($oldParameters);
			return NULL;
		} else {
			return $url;
		}
	}

}


interface IFrontRouteListFactory {
	/**
	 * @return FrontRouteList
	 */
	public function create();
}