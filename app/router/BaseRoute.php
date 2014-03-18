<?php

namespace Routers;

use Doctrine\ORM\EntityManager;
use Entity\Language;
use Nette;
use Nette\Application\Routers\Route;
use Nette\Utils\Strings;
use Repository\LanguageRepository;
use Repository\Location\LocationRepository;

class BaseRoute extends Nette\Object implements Nette\Application\IRouter
{

	const DEVICE = 'device';
	const LINK_SOURCE = 'ls';
	const AUTOLOGIN = 'l';
	const NEW_PASSWORD = 'np';
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
	 * @var \Repository\Location\LocationRepository
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

		if(array_key_exists('hostLocal', $params)) {
			list($host, $tld) = explode('.', $params['hostLocal'], 2);
			$host = 'tralandia';
		} else {
			list($host, $tld) = explode('.', $params['host'], 2);
		}

		$params['www'] = substr($params['www'], 0, -1);
		$domain = $params[self::LANGUAGE] . '.' . $host . '.' . $tld;
		if(!$primaryLocation = $this->getPrimaryLocationByDomain($domain)) {
			$params['www'] && $domain = $params['www'] . '.' . $domain;
			if(!$primaryLocation = $this->getPrimaryLocationByDomain($domain, TRUE)) {
				if(!$primaryLocation = $this->getPrimaryLocationByDomain($host . '.' . $tld)) {
					$primaryLocation = $this->locationRepository->findOneByIso('com');
				}
			}
		} else {
			$params[self::LANGUAGE] = $params['www'];
		}

		unset($params['host'], $params['hostLocal']);


		$params[self::PRIMARY_LOCATION] = $primaryLocation;

		$languageIso = $params[self::LANGUAGE];

		if($languageIso == 'www') {
			$language = $params[self::PRIMARY_LOCATION]->defaultLanguage;
		} else {
			$language = $this->languageRepository->findOneByIso($languageIso);
			if(!$language instanceof Language) $language = $params[self::PRIMARY_LOCATION]->defaultLanguage;
		}

		if(!$language->getSupported()) {
			$language = $this->languageRepository->find(CENTRAL_LANGUAGE);
		}

		$params[self::LANGUAGE] = $language;

		unset($params['www']);


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

		$params['host'] = $primaryLocation->getDomain()->getDomain();
		unset($params[self::PRIMARY_LOCATION]);

		if(array_key_exists('hostLocal', $defaults)) {
			$params['hostLocal'] = str_replace('tralandia.', 'tra-local.', $params['host']);
			unset($params['host']);
		}


		$language = $params[self::LANGUAGE];
		$languageIso = $language->iso == $primaryLocation->defaultLanguage->iso ? 'www' : $language->iso;
		$params[self::LANGUAGE] = $languageIso;

		$params['www'] = NULL;

		return $params;
	}


	/**
	 * @param $host
	 * @param bool $tryNewUrl
	 *
	 * @return \Doctrine\Common\Collections\ArrayCollection|\Entity\Location\Location[]|null
	 */
	private function getPrimaryLocationByDomain($host, $tryNewUrl = FALSE)
	{
		/** @var $domain \Entity\Domain */
		$domain = $this->domainRepository->findOneByDomain($host);
		if(!$domain && $tryNewUrl) {
			if($match = Strings::match($host, '~^([a-z]{2}|www)\.([a-z]{2})\.tralandia\.com$~')) {
				$domain = $this->domainRepository->findOneByDomain('tralandia.' . $match[2]);
			}
		}

		if(!$domain) {
			return NULL;
		} else {
			return $domain->getLocation();
		}
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
