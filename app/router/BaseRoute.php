<?php

namespace Routers;

use Doctrine\ORM\EntityManager;
use Nette;
use Nette\Application\Routers\Route;
use Repository\LanguageRepository;
use Repository\Location\LocationRepository;

class BaseRoute extends Nette\Object implements Nette\Application\IRouter
{

	const DEVICE = 'device';
	const AUTOLOGIN = 'l';
	const LANGUAGE = 'language';
	const PRIMARY_LOCATION = 'primaryLocation';
	const USE_ROOT_DOMAIN = 'useRootDomain';
	const ROOT_DOMAIN = 'com';
	const ROOT_LOCATION_SLUG = 'world';

	/**
	 * @var \Entity\Language
	 */
	protected $languageRepository;
	/**
	 * @var \Entity\Location\Location
	 */
	protected $locationRepository;

	protected $domainRepository;
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
	 * @param \Doctrine\ORM\EntityManager $em
	 */
	public function __construct($mask, $metadata, EntityManager $em)
	{
		$this->languageRepository = $em->getRepository(LANGUAGE_ENTITY);
		$this->locationRepository = $em->getRepository(LOCATION_ENTITY);
		$this->domainRepository = $em->getRepository(DOMAIN_ENTITY);

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
		$primaryLocation = $params[self::PRIMARY_LOCATION];

		if(isset($params['host'])) {
			$primaryLocation = $this->getPrimaryLocationFromHost($params['host']);
			unset($params['host']);
		} else {
			$primaryLocation = $this->locationRepository->findOneByIso($primaryLocation);
		}

		$params[self::PRIMARY_LOCATION] = $primaryLocation;

		$languageIso = $params[self::LANGUAGE];
		$language = $languageIso == 'www' ? $params[self::PRIMARY_LOCATION]->defaultLanguage : $this->languageRepository->findOneByIso($languageIso);
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
		/** @var $primaryLocation \Entity\Location\Location */
		$primaryLocation = $params[self::PRIMARY_LOCATION];

		$defaults = $this->route->getDefaults();

		if(array_key_exists('host', $defaults)) {
			$params['host'] = $primaryLocation->getDomain()->getDomain();
			unset($params[self::PRIMARY_LOCATION]);
		} else {
			$params[self::PRIMARY_LOCATION] = $primaryLocation->getIso();
		}


		$language = $params[self::LANGUAGE];
		$languageIso = $language->iso == $primaryLocation->defaultLanguage->iso ? 'www' : $language->iso;
		$params[self::LANGUAGE] = $languageIso;

		return $params;
	}

	private function getPrimaryLocationFromHost($host)
	{
		/** @var $domain \Entity\Domain */
		$domain = $this->domainRepository->findOneByDomain($host);
		return $domain->getLocation();
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
