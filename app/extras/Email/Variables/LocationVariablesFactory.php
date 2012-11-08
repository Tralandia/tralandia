<?php

namespace Extras\Email\Variables;

/**
 * LocationVariablesFactory class
 *
 * @author Dávid Ďurika
 */
class LocationVariablesFactory {

	protected $locationServiceFactory;

	public function __construct($locationServiceFactory) {
		$this->locationServiceFactory = $locationServiceFactory;
	}

	public function create(\Entity\Location\Location $location) {
		return new LocationVariables($this->locationServiceFactory->create($location));
	}
}