<?php

namespace Factory\Seo;

use Factory;
use Doctrine;
use Service\Seo\SeoService;
/**
 * SeoServiceFactory class
 *
 * @author Dávid Ďurika
 */
class SeoServiceFactory extends Factory\BaseServiceFactory {

	protected function createInstance($model, $entity) {
		$service = new SeoService($model, $entity);
		return $service;
	}

}