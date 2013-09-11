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

	protected $phraseDecoratorFactory;
	protected $locationDecoratorFactory;

	protected $routingPathSegmentRepositoryAccessor;
	protected $languageRepositoryAccessor;
	protected $pageRepositoryAccessor;
	protected $locationRepositoryAccessor;
	protected $rentalTypeRepositoryAccessor;
	protected $locationTypeRepositoryAccessor;

	public function injectDic(\Nette\DI\Container $dic) {
		$this->routingPathSegmentRepositoryAccessor = $dic->routingPathSegmentRepositoryAccessor;
		$this->languageRepositoryAccessor = $dic->languageRepositoryAccessor;
		$this->pageRepositoryAccessor = $dic->pageRepositoryAccessor;
		$this->locationRepositoryAccessor = $dic->locationRepositoryAccessor;
		$this->locationTypeRepositoryAccessor = $dic->locationTypeRepositoryAccessor;
		$this->rentalTypeRepositoryAccessor = $dic->rentalTypeRepositoryAccessor;
	}



	public function inject(Model\Phrase\IPhraseDecoratorFactory $phraseDecoratorFactory, Model\Location\ILocationDecoratorFactory $locationDecoratorFactory) {
		$this->phraseDecoratorFactory = $phraseDecoratorFactory;
		$this->locationDecoratorFactory = $locationDecoratorFactory;
	}

	public function needToRun()
	{
		return true;
	}

	public function run()
	{

		$languageList = $this->languageRepositoryAccessor->get()->findBySupported(TRUE);

		$this->persistPagesSegments($languageList);
		$this->persistLocationsSegments();
		$this->persistRentalTypesSegments($languageList);

		$this->languageRepositoryAccessor->get()->flush();
	}

	public function runTypes()
	{

		$languageList = $this->languageRepositoryAccessor->get()->findBySupported(TRUE);

		$this->persistRentalTypesSegments($languageList);

		$this->languageRepositoryAccessor->get()->flush();
	}

	public function runLocations()
	{
		$this->persistLocationsSegments();

		$this->languageRepositoryAccessor->get()->flush();
	}


	public function runPages()
	{
		$languageList = $this->languageRepositoryAccessor->get()->findBySupported(TRUE);

		$this->persistPagesSegments($languageList);

		$this->languageRepositoryAccessor->get()->flush();

	}

	protected function persistPagesSegments($languageList)
	{
		$pages = $this->pageRepositoryAccessor->get()->findAll();
		$centralLanguage = $this->languageRepositoryAccessor->get()->find(CENTRAL_LANGUAGE);

		foreach ($languageList as $languageId => $language) {
			foreach ($pages as $page) {
				$thisPathSegment = $this->translate($page->name, $language);
				if (Strings::length($thisPathSegment) == 0) $thisPathSegment = $this->translate($page->name, $centralLanguage);
				if (Strings::length($thisPathSegment) == 0) continue;

				$entity = $this->routingPathSegmentRepositoryAccessor->get()->createNew();
				$entity->primaryLocation = NULL;
				$entity->language = $language;
				$entity->pathSegment = $thisPathSegment;
				$entity->type = PathSegment::PAGE;
				$entity->entityId = $page->id;

				$this->pageRepositoryAccessor->get()->persist($entity);
			}
		}
	}

	protected function persistLocationsSegments()
	{
		$locations = $this->locationRepositoryAccessor->get()->findAllLocalityAndRegion();
		foreach ($locations as $location) {
			//$locationService = $this->locationDecoratorFactory->create($location);
			$country = $location->getPrimaryParent('country');

			$entity = $this->routingPathSegmentRepositoryAccessor->get()->createNew();
			$entity->primaryLocation = $country;
			$entity->language = NULL;

			$parent = $location->parent;
			$slug = $location->slug;

			$entity->pathSegment = $slug;
			$entity->type = PathSegment::LOCATION;
			$entity->entityId = $location->id;

			$this->locationRepositoryAccessor->get()->persist($entity);
		}
	}

	protected function persistRentalTypesSegments($languageList)
	{

		$rentalTypes = $this->rentalTypeRepositoryAccessor->get()->findAll();
		foreach ($languageList as $languageId => $language) {
			foreach ($rentalTypes as $type) {
				$entity = $this->routingPathSegmentRepositoryAccessor->get()->createNew();
				$entity->primaryLocation = NULL;
				$entity->language = $language;
				$entity->pathSegment = $this->translate($type->name, $language, 1, 0, 'nominative');
				$entity->type = PathSegment::RENTAL_TYPE;
				$entity->entityId = $type->id;

				$this->rentalTypeRepositoryAccessor->get()->persist($entity);
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
