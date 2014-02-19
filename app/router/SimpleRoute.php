<?php

namespace Routers;

use Nette;
use Nette\Application\Routers\Route;
use Nette\Utils\Strings;
use Nette\Utils\Arrays;
use Tralandia\BaseDao;

class SimpleRoute extends BaseRoute
{

	/**
	 * @var BaseDao
	 */
	public $pageDao;

	/**
	 * @var array
	 */
	protected $pagesDestinations;


	/**
	 * @param \Nette\Http\IRequest $httpRequest
	 * @return \Nette\Application\Request|NULL
	 */
	public function match(Nette\Http\IRequest $httpRequest)
	{

		$route = $this->route;
		if ($appRequest = $route->match($httpRequest)) {
			//$presenter = $appRequest->getPresenterName();

			$params = $appRequest->getParameters();
			$params = $this->filterIn($params);

			//$appRequest->setPresenterName($presenter);
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

		$presenterName = $appRequest->getPresenterName();
		list($module,) = explode(':', $presenterName, 2);
		$params = $appRequest->getParameters();
		$params = $this->filterOut($params);

		if($module == 'Admin') {
			if(array_key_exists(self::PRIMARY_LOCATION, $params)) $params[self::PRIMARY_LOCATION] = 'com';
			$params[self::LANGUAGE] = 'www';
		}

		//$params['action'] = $this->actionName;
		//$appRequest->setPresenterName($this->presenterName);

		$searchParametersName = FrontRoute::$pathParametersMapper;
		foreach($params as $paramName => $param) {
			if(in_array($paramName, $searchParametersName)) return NULL;
			if(!is_array($param) && !is_scalar($param) && !is_bool($param) && $param !== NULL) {
				return NULL;
			}
		}

		if($this->skipLink($presenterName, $params['action'])) return NULL;

		$appRequest->setParameters($params);

		$url = $this->route->constructUrl($appRequest, $refUrl);

		if(!$url) {
			return NULL;
		} else {
			return $url;
		}
	}

	protected function skipLink($presenter, $action)
	{
		$destination = ':'.$presenter.':'.$action;
		if(in_array($destination, [':Front:RentalList:redirectToFavorites'])) {
			return TRUE;
		}

		$destinations = $this->getPagesDestinations();

		return array_key_exists($destination, $destinations);
	}

	protected function getPagesDestinations()
	{
		if(!$this->pagesDestinations) {
			$qb = $this->pageDao->createQueryBuilder('p');
			$qb->select('p.id, p.destination');
			$destinations = $qb->getQuery()->getArrayResult();
			foreach($destinations as $destination) {
				$this->pagesDestinations[$destination['destination']] = $destination['id'];
			}
		}
		return $this->pagesDestinations;
	}


}


interface ISimpleRouteFactory {
	/**
	 * @param $mask
	 * @param $metadata
	 *
	 * @return SimpleRoute
	 */
	public function create($mask, $metadata);
}
