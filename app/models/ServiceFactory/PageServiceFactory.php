<?php

namespace Factory;

use Factory;
use Doctrine;
use Service\Location\LocationService;
/**
 * PageServiceFactory class
 *
 * @author Dávid Ďurika
 */
class PageServiceFactory extends Factory\BaseServiceFactory {

	protected function createInstance($model, $entity) {
		$service = new PageService($model, $entity);
		return $service;
	}

}