<?php

namespace Service\Robot;

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
	protected $attractionTypeRepositoryAccessor;
	protected $locationRepositoryAccessor;
	protected $rentalTypeRepositoryAccessor;
	protected $rentalTagRepositoryAccessor;

	public function injectDic(\Nette\DI\Container $dic) {
		$this->routingPathSegmentRepositoryAccessor = $dic->routingPathSegmentRepositoryAccessor;
		$this->languageRepositoryAccessor = $dic->languageRepositoryAccessor;
		$this->pageRepositoryAccessor = $dic->pageRepositoryAccessor;
		$this->attractionTypeRepositoryAccessor = $dic->attractionTypeRepositoryAccessor;
		$this->locationRepositoryAccessor = $dic->locationRepositoryAccessor;
		$this->rentalTypeRepositoryAccessor = $dic->rentalTypeRepositoryAccessor;
		$this->rentalTagRepositoryAccessor = $dic->rentalTagRepositoryAccessor;
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
		$this->persistAtractionTypesSegments($languageList);
		$this->persistLocationsSegments();
		$this->persistRentalTypesSegments($languageList);
		$this->persistTagsSegments($languageList);
		
		$this->rentalTypeRepositoryAccessor->get()->flush();
	}

	protected function persistPagesSegments($languageList)
	{
		$pages = $this->pageRepositoryAccessor->get()->findAll();

		foreach ($languageList as $languageId => $language) {
			foreach ($pages as $page) {
				$entity = $this->routingPathSegmentRepositoryAccessor->get()->createNew();
				$entity->primaryLocation = NULL;
				$entity->language = $language;
				$entity->pathSegment = $this->translate($page->name, $language);
				$entity->type = PathSegment::PAGE;
				$entity->entityId = $page->id;

				$this->rentalTypeRepositoryAccessor->get()->persist($entity);
			}
		}
	}

	protected function persistAtractionTypesSegments($languageList)
	{
		$attractionTypes = $this->attractionTypeRepositoryAccessor->get()->findAll();

		foreach ($languageList as $languageId => $language) {
			foreach ($attractionTypes as $type) {
				$entity = $this->routingPathSegmentRepositoryAccessor->get()->createNew();
				$entity->primaryLocation = NULL;
				$entity->language = $language;
				$entity->pathSegment = $this->translate($type->name, $language);
				$entity->type = PathSegment::ATTRACTION_TYPE;
				$entity->entityId = $type->id;

				$this->rentalTypeRepositoryAccessor->get()->persist($entity);
			}
		}

	}

	protected function persistLocationsSegments()
	{
		$locations = $this->locationRepositoryAccessor->get()->findAll();
		foreach ($locations as $location) {
			$locationService = $this->locationDecoratorFactory->create($location);
			$country = $locationService->getParent('country');

			$entity = $this->routingPathSegmentRepositoryAccessor->get()->createNew();
			$entity->primaryLocation = $country;
			$entity->language = NULL;

			$parent = $location->parent;
			if(in_array($location->type->slug, array('region', 'locality')) && $parent && !$parent->domain) {
				$slug = $parent->slug . '-' . $location->slug;
			} else {
				$slug = $location->slug;
			}

			$entity->pathSegment = $slug;
			$entity->type = PathSegment::LOCATION;
			$entity->entityId = $location->id;

			$this->rentalTypeRepositoryAccessor->get()->persist($entity);
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
				$entity->pathSegment = $this->translate($type->name, $language);
				$entity->type = PathSegment::RENTAL_TYPE;
				$entity->entityId = $type->id;

				$this->rentalTypeRepositoryAccessor->get()->persist($entity);
			}
		}
	}

	protected function persistTagsSegments($languageList)
	{

		$tags = $this->rentalTagRepositoryAccessor->get()->findAll();
		foreach ($languageList as $languageId => $language) {
			foreach ($tags as $tag) {
				$entity = $this->routingPathSegmentRepositoryAccessor->get()->createNew();
				$entity->primaryLocation = NULL;
				$entity->language = $language;
				$entity->pathSegment = $this->translate($tag->name, $language);
				$entity->type = PathSegment::TAG;
				$entity->entityId = $tag->id;

				$this->rentalTagRepositoryAccessor->get()->persist($entity);
			}
		}
	}

	protected function translate($phrase, $language)
	{
		$phrase = $this->phraseDecoratorFactory->create($phrase);
		$translation = $phrase->getTranslation($language);
		return $translation ? Strings::webalize($translation->translation) : $phrase->id.'_'.$language->id;
	}


}