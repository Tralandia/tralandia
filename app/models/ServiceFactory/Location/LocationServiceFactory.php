<?php

namespace Factory\Location;

use Factory;
use Doctrine;
use Service\Location\LocationService;
/**
 * LocationServiceFactory class
 *
 * @author Dávid Ďurika
 */
class LocationServiceFactory extends Factory\BaseServiceFactory {

	protected $locationRepositoryAccessor;

	public function __construct($entityFactory, $locationRepositoryAccessor, Doctrine\ORM\EntityManager $model) {
		parent::__construct($model, $entityFactory);
		$this->locationRepositoryAccessor = $locationRepositoryAccessor;
	}

	protected function createInstance($model, $entity) {
		$service = new LocationService($model, $entity);
		$service->setLocationRepositoryAccessor($this->locationRepositoryAccessor);
		return $service;
	}

}