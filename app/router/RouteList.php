<?php

namespace Routers;

use Nette;
use Repository\LanguageRepository;
use Repository\Location\LocationRepository;

abstract class BaseRouteList extends Nette\Object implements Nette\Application\IRouter
{

	const PARAM_LANGUAGE = 'language';
	const PARAM_PRIMARY_LOCATION = 'primaryLocation';

	/**
	 * @var \Entity\Language
	 */
	protected $languageRepository;
	/**
	 * @var \Entity\Location\Location
	 */
	protected $locationRepository;


	/**
	 * @param \Repository\LanguageRepository $languageRepository
	 * @param \Repository\Location\LocationRepository $locationRepository
	 * @param string $module
	 */
	public function __construct(LanguageRepository $languageRepository, LocationRepository $locationRepository,
								$module = NULL)
	{
		$this->languageRepository = $languageRepository;
		$this->locationRepository = $locationRepository;
	}

	/**
	 * @param array $params
	 *
	 * @return array
	 */
	public function filterIn(array $params)
	{
		$primaryLocationIso = $params[self::PARAM_PRIMARY_LOCATION];
		$primaryLocation = $this->locationRepository->findOneByIso($primaryLocationIso);
		$params[self::PARAM_PRIMARY_LOCATION] = $primaryLocation;

		$languageIso = $params[self::PARAM_LANGUAGE];
		$language = $languageIso == 'www' ? $primaryLocation->defaultLanguage : $this->languageRepository->findOneByIso($languageIso);
		$params[self::PARAM_LANGUAGE] = $language;

		return $params;
	}

	/**
	 * @param array $params
	 *
	 * @return array
	 */
	public function filterOut(array $params)
	{
		$primaryLocation = $params[self::PARAM_PRIMARY_LOCATION];
		$params[self::PARAM_PRIMARY_LOCATION] = $primaryLocation->iso;


		$language = $params[self::PARAM_LANGUAGE];
		$languageIso = $language->iso == $primaryLocation->defaultLanguage->iso ? 'www' : $language->iso;
		$params[self::PARAM_LANGUAGE] = $languageIso;

		return $params;
	}

}