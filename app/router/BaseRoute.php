<?php

namespace Routers;

use Nette;
use Nette\Application\Routers\Route;
use Repository\LanguageRepository;
use Repository\Location\LocationRepository;

abstract class BaseRoute extends Nette\Object implements Nette\Application\IRouter
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
		$this->presenterName = $metadata['presenter'];
		$this->actionName = $metadata['action'];
		$this->route = new Route($mask, $metadata);

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