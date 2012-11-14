<?php

namespace Service\Robot;

use Entity\Routing\PathSegment;

/**
 * GeneratePathSegmentsRobot class
 *
 * @author Dávid Ďurika
 */
class GeneratePathSegmentsRobot extends \Nette\Object implements IRobot {

	protected $languageRepositoryAccessor;
	protected $pageRepositoryAccessor;
	protected $attractionTypeRepositoryAccessor;
	protected $locationRepositoryAccessor;
	protected $rentalTypeRepositoryAccessor;
	protected $locationServiceFactory;

	public function __construct(
			$languageRepositoryAccessor,
			$pageRepositoryAccessor,
			$attractionTypeRepositoryAccessor,
			$locationRepositoryAccessor,
			$rentalTypeRepositoryAccessor,
			$locationServiceFactory
		) 
	{
		list($this->languageRepositoryAccessor,
			$this->pageRepositoryAccessor,
			$this->attractionTypeRepositoryAccessor,
			$this->locationRepositoryAccessor,
			$this->rentalTypeRepositoryAccessor,
			$this->locationServiceFactory) = func_get_args();
	}

	public function needToRun() {
		return true;
	}

	public function run() {

		$languageList = $this->languageRepositoryAccessor->get()->getPairs('id', 'iso', array('supported' => TRUE));

		$segments = array();
		$segments = $segments + $this->getPagesSegments($languageList);
		$segments = $segments + $this->getAtractionTypesSegments($languageList);
		$segments = $segments + $this->getLocationsSegments();
		$segments = $segments + $this->getRentalTypesSegments($languageList);
		//$segments = $segments + $this->getTagsSegments($languageList);
		debug($segments);

	}

	protected function getPagesSegments($languageList) {
		$segments = array();
		$pages = $this->pageRepositoryAccessor->get()->getPairs('id', array('name', 'id'));

		foreach ($languageList as $languageId => $languageIso) {
			foreach ($pages as $pageId => $pageName) {
				$segments[] = array(
					'country_id' => 0,
					'language_id' => $languageId,
					'pathSegment' => $this->translate($pageName, $languageId),
					'type' => PathSegment::PAGE,
					'entityId' => $pageId,
				);
			}
		}

		return $segments;
	}

	protected function getAtractionTypesSegments($languageList) {
		$segments = array();
		$attractionTypes = $this->attractionTypeRepositoryAccessor->get()->getPairs('id', array('name', 'id'));

		foreach ($languageList as $languageId => $languageIso) {
			foreach ($attractionTypes as $typeId => $typeName) {
				$segments[] = array(
					'country_id' => 0,
					'language_id' => $languageId,
					'pathSegment' => $this->translate($typeName, $languageId),
					'type' => PathSegment::ATTRACTION_TYPE,
					'entityId' => $typeId,
				);
			}
		}

		return $segments;
	}

	protected function getLocationsSegments() {
		$segments = array();
		$locations = $this->locationRepositoryAccessor->get()->findAll();
		foreach ($locations as $location) {
			$locationService = $this->locationServiceFactory->create($location);
			$segments[] = array(
				'country_id' => $locationService->getParent('country'),
				'language_id' => 0,
				'pathSegment' => $location->slug,
				'type' => PathSegment::LOCATION,
				'entityId' => $location->id,				
			);
		}
		return $segments;
	}

	protected function getRentalTypesSegments($languageList) {
		$segments = array();

		$rentalTypes = $this->rentalTypeRepositoryAccessor->get()->getPairs('id', array('name', 'id'));
		foreach ($languageList as $languageId => $languageIso) {
			foreach ($rentalTypes as $typeId => $typeName) {
				$segments[] = array(
					'country_id' => 0,
					'language_id' => $languageId,
					'pathSegment' => $this->translate($typeName, $languageId),
					'type' => PathSegment::RENTAL_TYPE,
					'entityId' => $typeId,				
				);
			}
		}
		return $segments;
	}

	protected function getTagsSegments($languageList) {
		$segments = array();
		// @todo method or operation is not implemented
		throw new \Nette\NotImplementedException('Requested method or operation is not implemented');
		return $segments;
	}

	protected function translate($phraseId, $languageId) {
		return $phraseId . '_' . $languageId;
	}


}