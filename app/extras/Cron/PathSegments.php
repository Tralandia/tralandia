<?php

namespace Extras\Cron;

use Service as S;

class PathSegments {

	const PAGE = 2;
	const ATTRACTION_TYPE = 4;
	const LOCATION = 6;
	const RENTAL_TYPE = 8;
	const TAG = 10;

	/**
	 * 
	 */
	public function updatePathsegments() {

		$languageList = \Service\Dictionary\LanguageList::getPairs('id', 'iso', array('supported' => true));

		$segments = array();
		$segments = $segments + $this->getPagesSegments($languageList);
		$segments = $segments + $this->getAtractionTypesSegments($languageList);
		$segments = $segments + $this->getLocationsSegments();
		$segments = $segments + $this->getRentalTypesSegments($languageList);
		$segments = $segments + $this->getTagsSegments($languageList);
		debug($segments);

	}

	public function getPagesSegments($languageList) {
		$segments = array();
		$pages = S\Page\PageList::getPairs('id', array('name', 'id'));

		foreach ($languageList as $languageId => $languageIso) {
			foreach ($pages as $pageId => $pageName) {
				$segments[] = array(
					'country_id' => 0,
					'language_id' => $languageId,
					'pathSegment' => $this->translate($pageName, $languageId),
					'type' => self::PAGE,
					'entityId' => $pageId,
				);
			}
		}

		return $segments;
	}

	public function getAtractionTypesSegments($languageList) {
		$segments = array();
		$attractionTypes = S\Attraction\TypeList::getPairs('id', array('name', 'id'));

		foreach ($languageList as $languageId => $languageIso) {
			foreach ($attractionTypes as $typeId => $typeName) {
				$segments[] = array(
					'country_id' => 0,
					'language_id' => $languageId,
					'pathSegment' => $this->translate($typeName, $languageId),
					'type' => self::ATTRACTION_TYPE,
					'entityId' => $typeId,
				);
			}
		}

		return $segments;
	}

	public function getLocationsSegments() {
		$segments = array();
		$locations = S\Location\LocationList::getAll();
		foreach ($locations as $location) {
			$segments[] = array(
				'country_id' => $location->getParent('country'),
				'language_id' => 0,
				'pathSegment' => $location->slug,
				'type' => self::LOCATION,
				'entityId' => $location->id,				
			);
		}
		return $segments;
	}

	public function getRentalTypesSegments($languageList) {
		$segments = array();
		$rentalTypes = S\Rental\TypeList::getPairs('id', array('name', 'id'));
		foreach ($languageList as $languageId => $languageIso) {
			foreach ($rentalTypes as $typeId => $typeName) {
				$segments[] = array(
					'country_id' => 0,
					'language_id' => $languageId,
					'pathSegment' => $this->translate($typeName, $languageId),
					'type' => self::RENTAL_TYPE,
					'entityId' => $typeId,				
				);
			}
		}
		return $segments;
	}

	public function getTagsSegments($languageList) {
		$segments = array();
		// @todo method or operation is not implemented
		throw new \Nette\NotImplementedException('Requested method or operation is not implemented');
		return $segments;
	}

	public function translate($phraseId, $languageId) {
		return $phraseId . '_' . $languageId;
	}

}