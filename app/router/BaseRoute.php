<?php

namespace Routers;

use Nette;
use Nette\Application\Routers\Route;
use Repository\LanguageRepository;
use Repository\Location\LocationRepository;

class BaseRoute extends Nette\Object implements Nette\Application\IRouter
{

	const LANGUAGE = 'language';
	const PRIMARY_LOCATION = 'primaryLocation';

	/**
	 * @var \Entity\Language
	 */
	protected $languageRepository;
	/**
	 * @var \Entity\Location\Location
	 */
	protected $locationRepository;

	/**
	 * @var \Nette\Application\Routers\Route
	 */
	protected $route;

	/**
	 * @var string
	 */
	protected $presenterName;

	/**
	 * @var string
	 */
	protected $actionName;

	/**
	 * @param string $mask
	 * @param array $metadata
	 * @param \Repository\LanguageRepository $languageRepository
	 * @param \Repository\Location\LocationRepository $locationRepository
	 */
	public function __construct($mask, $metadata, LanguageRepository $languageRepository,
								LocationRepository $locationRepository)
	{
		$this->languageRepository = $languageRepository;
		$this->locationRepository = $locationRepository;

		if(!isset($metadata[self::PRIMARY_LOCATION])) $metadata[self::PRIMARY_LOCATION] = 'sk';
		if(!isset($metadata[self::LANGUAGE])) $metadata[self::LANGUAGE] = 'www';
		$this->presenterName = $metadata['presenter'];
		$this->actionName = $metadata['action'];

		//d($mask, $metadata);
		$this->route = new Route($mask, $metadata);

	}


	/**
	 * @param Nette\Http\IRequest $httpRequest
	 *
	 * @return Nette\Application\Request|NULL
	 */
	public function match(Nette\Http\IRequest $httpRequest)
	{

		$appRequest = $this->route->match($httpRequest);

		if ($appRequest) {
			$params = $appRequest->getParameters();
			$params = $this->filterIn($params);
			//$appRequest->setPresenterName($presenter);
			$appRequest->setParameters($params);

			return $appRequest;
		}

		return NULL;
	}


	/**
	 * @param Nette\Application\Request $appRequest
	 * @param Nette\Http\Url $refUrl
	 *
	 * @return NULL|string
	 */
	public function constructUrl(Nette\Application\Request $appRequest, Nette\Http\Url $refUrl)
	{
		$appRequest = clone $appRequest;
		$params = $appRequest->getParameters();
		$params = $this->filterOut($params);
		$appRequest->setParameters($params);

		$url = $this->route->constructUrl($appRequest, $refUrl);

		if($url) {
			return $url;
		}

		return NULL;
	}


		/**
	 * @param array $params
	 *
	 * @return array
	 */
	public function filterIn(array $params)
	{
		$primaryLocationIso = $params[self::PRIMARY_LOCATION];
		$primaryLocation = $this->locationRepository->findOneByIso($primaryLocationIso);
		$params[self::PRIMARY_LOCATION] = $primaryLocation;

		$languageIso = $params[self::LANGUAGE];
		$language = $languageIso == 'www' ? $primaryLocation->defaultLanguage : $this->languageRepository->findOneByIso($languageIso);
		$params[self::LANGUAGE] = $language;

		return $params;
	}

	/**
	 * @param array $params
	 *
	 * @return array
	 */
	public function filterOut(array $params)
	{
		$primaryLocation = $params[self::PRIMARY_LOCATION];
		$params[self::PRIMARY_LOCATION] = $primaryLocation->iso;


		$language = $params[self::LANGUAGE];
		$languageIso = $language->iso == $primaryLocation->defaultLanguage->iso ? 'www' : $language->iso;
		$params[self::LANGUAGE] = $languageIso;

		return $params;
	}

}

interface IBaseRouteFactory {

	/**
	 * @param $mask
	 * @param $metadata
	 *
	 * @return BaseRoute
	 */
	public function create($mask, $metadata);
}
