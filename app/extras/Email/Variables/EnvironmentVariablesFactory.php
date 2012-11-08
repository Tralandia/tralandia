<?php

namespace Extras\Email\Variables;

/**
 * EnvironmentVariablesFactory class
 *
 * @author Dávid Ďurika
 */
class EnvironmentVariablesFactory {

	protected $locationServiceFactory;
	protected $languageServiceFactory;

	public function __construct($locationServiceFactory, $languageServiceFactory) {
		$this->locationServiceFactory = $locationServiceFactory;
		$this->languageServiceFactory = $languageServiceFactory;
	}

	public function create(\Entity\Location\Location $location, \Entity\Language $language) {
		return new EnvironmentVariables(
			$this->locationServiceFactory->create($location),
			$this->languageServiceFactory->create($language)
		);
	}
}