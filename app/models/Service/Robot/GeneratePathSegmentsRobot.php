<?php

namespace Service\Robot;

use Entity\Routing\PathSegment;
use Nette\Utils\Strings;

/**
 * GeneratePathSegmentsRobot class
 *
 * @author Dávid Ďurika
 */
class GeneratePathSegmentsRobot extends \Nette\Object implements IRobot 
{

	protected $routingPathSegmentRepositoryAccessor;
	protected $routingPathSegmentEntityFactory;
	protected $phraseServiceFactory;
	protected $languageRepositoryAccessor;
	protected $pageRepositoryAccessor;
	protected $attractionTypeRepositoryAccessor;
	protected $locationRepositoryAccessor;
	protected $rentalTypeRepositoryAccessor;
	protected $locationServiceFactory;

	public function __construct(
			$routingPathSegmentRepositoryAccessor,
			$routingPathSegmentEntityFactory,
			$phraseServiceFactory,
			$languageRepositoryAccessor,
			$pageRepositoryAccessor,
			$attractionTypeRepositoryAccessor,
			$locationRepositoryAccessor,
			$rentalTypeRepositoryAccessor,
			$locationServiceFactory
		) 
	{
		list($this->routingPathSegmentRepositoryAccessor,
			$this->routingPathSegmentEntityFactory,
			$this->phraseServiceFactory,
			$this->languageRepositoryAccessor,
			$this->pageRepositoryAccessor,
			$this->attractionTypeRepositoryAccessor,
			$this->locationRepositoryAccessor,
			$this->rentalTypeRepositoryAccessor,
			$this->locationServiceFactory) = func_get_args();
	}

	public function needToRun()
	{
		return true;
	}

	public function run()
	{

		//$languageList = $this->languageRepositoryAccessor->get()->getPairs('id', 'iso', array('supported' => TRUE));
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
				$entity = $this->routingPathSegmentEntityFactory->create();
				$entity->country = NULL;
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
				$entity = $this->routingPathSegmentEntityFactory->create();
				$entity->country = NULL;
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
			$locationService = $this->locationServiceFactory->create($location);
			$country = $locationService->getParent('country');

			$entity = $this->routingPathSegmentEntityFactory->create();
			$entity->country = $country;
			$entity->language = NULL;
			$entity->pathSegment = $location->slug;
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
				$entity = $this->routingPathSegmentEntityFactory->create();
				$entity->country = NULL;
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
		// @todo method or operation is not implemented
		throw new \Nette\NotImplementedException('Requested method or operation is not implemented');
	}

	protected function translate($phrase, $language)
	{
		$phrase = $this->phraseServiceFactory->create($phrase);
		$translation = $phrase->getTranslation($language);
		return $translation ? Strings::webalize($translation->translation) : $phrase->id.'_'.$language->id;
	}


}