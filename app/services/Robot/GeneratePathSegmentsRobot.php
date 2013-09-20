<?php

namespace Robot;

use Model;
use Entity\Routing\PathSegment;
use Nette\Utils\Strings;

/**
 * GeneratePathSegmentsRobot class
 *
 * @author Dávid Ďurika
 */
class GeneratePathSegmentsRobot extends \Nette\Object implements IRobot
{


	protected $routingPathSegmentDao;
	protected $languageDao;
	protected $pageDao;
	protected $locationDao;
	protected $rentalTypeDao;
	protected $locationTypeDao;

	public function injectDic(\Nette\DI\Container $dic) {
		$this->routingPathSegmentDao = $dic->getService('doctrine.default.entityManager')->getDao(PATH_SEGMENT_ENTITY);
		$this->languageDao = $dic->getService('doctrine.default.entityManager')->getDao(LANGUAGE_ENTITY);
		$this->pageDao = $dic->getService('doctrine.default.entityManager')->getDao(PAGE_ENTITY);
		$this->locationDao = $dic->getService('doctrine.default.entityManager')->getDao(LOCATION_ENTITY);
		$this->locationTypeDao = $dic->getService('doctrine.default.entityManager')->getDao(LOCATION_TYPE_ENTITY);
		$this->rentalTypeDao = $dic->getService('doctrine.default.entityManager')->getDao(RENTAL_TYPE_ENTITY);
	}



	public function needToRun()
	{
		return true;
	}

	public function run()
	{

		$languageList = $this->languageDao->findBySupported(TRUE);

		$this->persistPagesSegments($languageList);
		$this->persistLocationsSegments();
		$this->persistRentalTypesSegments($languageList);

		$this->languageDao->flush();
	}

	public function runTypes()
	{

		$languageList = $this->languageDao->findBySupported(TRUE);

		$this->persistRentalTypesSegments($languageList);

		$this->languageDao->flush();
	}

	public function runLocations()
	{
		$this->persistLocationsSegments();

		$this->languageDao->flush();
	}


	public function runPages()
	{
		$languageList = $this->languageDao->findBySupported(TRUE);

		$this->persistPagesSegments($languageList);

		$this->languageDao->flush();

	}

	protected function persistPagesSegments($languageList)
	{
		$pages = $this->pageDao->findAll();
		$centralLanguage = $this->languageDao->find(CENTRAL_LANGUAGE);

		foreach ($languageList as $languageId => $language) {
			foreach ($pages as $page) {
				if(!$page->generatePathSegment) continue;

				$thisPathSegment = $this->translate($page->titlePattern, $language);
				if (Strings::length($thisPathSegment) == 0) $thisPathSegment = $this->translate($page->titlePattern, $centralLanguage);
				if (Strings::length($thisPathSegment) == 0) continue;

				$entity = $this->routingPathSegmentDao->createNew();
				$entity->primaryLocation = NULL;
				$entity->language = $language;
				$entity->pathSegment = $thisPathSegment;
				$entity->type = PathSegment::PAGE;
				$entity->entityId = $page->id;

				$this->pageDao->persist($entity);
			}
		}
	}

	protected function persistLocationsSegments()
	{
		$locations = $this->locationDao->findAllLocalityAndRegion();
		foreach ($locations as $location) {
			$country = $location->getPrimaryParent('country');

			$entity = $this->routingPathSegmentDao->createNew();
			$entity->primaryLocation = $country;
			$entity->language = NULL;

			$parent = $location->parent;
			$slug = $location->slug;

			$entity->pathSegment = $slug;
			$entity->type = PathSegment::LOCATION;
			$entity->entityId = $location->id;

			$this->locationDao->persist($entity);
		}
	}

	protected function persistRentalTypesSegments($languageList)
	{

		$rentalTypes = $this->rentalTypeDao->findAll();
		foreach ($languageList as $languageId => $language) {
			foreach ($rentalTypes as $type) {
				$entity = $this->routingPathSegmentDao->createNew();
				$entity->primaryLocation = NULL;
				$entity->language = $language;
				$entity->pathSegment = $this->translate($type->name, $language, 1, 0, 'nominative');
				$entity->type = PathSegment::RENTAL_TYPE;
				$entity->entityId = $type->id;

				$this->rentalTypeDao->persist($entity);
			}
		}
	}

	protected function translate($phrase, $language, $plural = NULL, $gender = NULL, $case = NULL)
	{
		$translation = $phrase->getTranslation($language);
		if ($plural && is_object($translation)) {
			$variation = $translation->getVariation($plural, $gender, $case);
			return $variation ? Strings::webalize($variation) : $phrase->id.'_'.$language->id;
		} else {
			return $translation ? Strings::webalize($translation->translation) : $phrase->id.'_'.$language->id;
		}
	}


}
