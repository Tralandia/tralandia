<?php

namespace Routers;

use Nette;
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