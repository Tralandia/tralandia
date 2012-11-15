<?php

namespace Service\Robot;

use Entity\Routing\PathSegment;

/**
 * GeneratePathSegmentsRobot class
 *
 * @author Dávid Ďurika
 */
class GeneratePathSegmentsRobot extends \Nette\Object implements IRobot 
{

	protected $routingPathSegmentRepositoryAccessor;
	protected $routingPathSegmentEntityFactory;
	protected $languageRepositoryAccessor;
	protected $pageRepositoryAccessor;
	protected $attractionTypeRepositoryAccessor;
	protected $locationRepositoryAccessor;
	protected $rentalTypeRepositoryAccessor;
	protected $locationServiceFactory;

	public function __construct(
			$routingPathSegmentRepositoryAccessor,
			$routingPathSegmentEntityFactory,
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

		$languageList = $this->languageRepositoryAccessor->get()->getPairs('id', 'iso', array('supported' => TRUE));

		$this->persistPagesSegments($languageList);
		$this->persistAtractionTypesSegments($languageList);
		$this->persistLocationsSegments();
		$this->persistRentalTypesSegments($languageList);
		//$this->persistTagsSegments($languageList);
		
		$this->rentalTypeRepositoryAccessor->get()->flush();
	}

	protected function persistPagesSegments($languageList)
	{
		$pages = $this->pageRepositoryAccessor->get()->getPairs('id', array('name', 'id'));

		foreach ($languageList as $languageId => $languageIso) {
			foreach ($pages as $pageId => $pageName) {
				$entity = $this->routingPathSegmentEntityFactory->create();
				$entity->country = NULL;
				$entity->language = $languageId;
				$entity->pathSegment = $this->translate($pageName, $languageId);
				$entity->type = PathSegment::PAGE;
				$entity->entityId = $pageId;

				$this->rentalTypeRepositoryAccessor->get()->persist($entity);
			}
		}
	}

	protected function persistAtractionTypesSegments($languageList)
	{
		$attractionTypes = $this->attractionTypeRepositoryAccessor->get()->getPairs('id', array('name', 'id'));

		foreach ($languageList as $languageId => $languageIso) {
			foreach ($attractionTypes as $typeId => $typeName) {
				$entity = $this->routingPathSegmentEntityFactory->create();
				$entity->country = NULL;
				$entity->language = $languageId;
				$entity->pathSegment = $this->translate($typeName, $languageId);
				$entity->type = PathSegment::ATTRACTION_TYPE;
				$entity->entityId = $typeId;

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

		$rentalTypes = $this->rentalTypeRepositoryAccessor->get()->getPairs('id', array('name', 'id'));
		foreach ($languageList as $languageId => $languageIso) {
			foreach ($rentalTypes as $typeId => $typeName) {
				$entity = $this->routingPathSegmentEntityFactory->create();
				$entity->country = NULL;
				$entity->language = $languageId;
				$entity->pathSegment = $this->translate($typeName, $languageId);
				$entity->type = PathSegment::RENTAL_TYPE;
				$entity->entityId = $typeId;

				$this->rentalTypeRepositoryAccessor->get()->persist($entity);
			}
		}
	}

	protected function persistTagsSegments($languageList)
	{
		// @todo method or operation is not implemented
		throw new \Nette\NotImplementedException('Requested method or operation is not implemented');
	}

	protected function translate($phraseId, $languageId)
	{
		return $phraseId . '_' . $languageId;
	}


}