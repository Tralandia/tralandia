<?php

namespace Routers;

use Kdyby;
use Nette;
use Nette\Application\Routers\Route;


class OwnerRouteList extends Nette\Application\Routers\RouteList
{

	const PARAM_LANGUAGE = 'language';
	const PARAM_PRIMARY_LOCATION = 'primaryLocation';

	protected $languageRepositoryAccessor;
	protected $locationRepositoryAccessor;

	public function __construct($languageRepositoryAccessor, $locationRepositoryAccessor)
	{
		$this->languageRepositoryAccessor = $languageRepositoryAccessor;
		$this->locationRepositoryAccessor = $locationRepositoryAccessor;
		parent::__construct('Owner');

		$this[] = new Route('//<language ([a-z]{2}|www)>.<primaryLocation [a-z]{2,3}>.%domain%/owner/<presenter>[/<action>[/<id>]]', array(
			self::PARAM_PRIMARY_LOCATION => 'sk',
			self::PARAM_LANGUAGE => 'www',
		));

	}

	public function filterIn(array $params)
	{
		$primaryLocationIso = $params[self::PARAM_PRIMARY_LOCATION];
		$primaryLocation = $this->locationRepositoryAccessor->get()->findOneByIso($primaryLocationIso);
		$params[self::PARAM_PRIMARY_LOCATION] = $primaryLocation;

		$languageIso = $params[self::PARAM_LANGUAGE];
		$language = $languageIso == 'www' ? $primaryLocation->defaultLanguage : $this->languageRepositoryAccessor->get()->findOneByIso($languageIso);
		$params[self::PARAM_LANGUAGE] = $language;

		return $params;
	}

	public function filterOut(array $params)
	{
		$primaryLocation = $params[self::PARAM_PRIMARY_LOCATION];
		$params[self::PARAM_PRIMARY_LOCATION] = $primaryLocation->iso;


		$language = $params[self::PARAM_LANGUAGE];
		$languageIso = $language->iso == $primaryLocation->defaultLanguage->iso ? 'www' : $language->iso;
		$params[self::PARAM_LANGUAGE] = $languageIso;

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
			$appRequest->setPresenterName($this->getModule() . $presenter);

			$params = $appRequest->getParameters();
			$params = $this->filterIn($params);
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