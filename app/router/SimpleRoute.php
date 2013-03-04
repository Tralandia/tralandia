<?php

namespace Routers;

use Nette;
use Nette\Application\Routers\Route;
use Nette\Utils\Strings;
use Nette\Utils\Arrays;

class SimpleRoute extends BaseRoute
{

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

		$params = $appRequest->getParameters();
		$params = $this->filterOut($params);

		//$params['action'] = $this->actionName;
		//$appRequest->setPresenterName($this->presenterName);

		foreach($params as $param) {
			if(!is_scalar($param) && !is_bool($param) && $param !== NULL) {
				return NULL;
			}
		}

		$appRequest->setParameters($params);

		$url = $this->route->constructUrl($appRequest, $refUrl);

		// @todo @hack
		if(\Nette\Utils\Strings::endsWith($url, '/front/')) {
			$url = substr($url, 0, -6);
		}

		if(!$url) {
			return NULL;
		} else {
			return $url;
		}
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