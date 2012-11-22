<?php

namespace Service\Rental;

use Service, Doctrine, Entity;

/**
 * @author Dávid Ďurika
 */
class RentalService extends Service\BaseService 
{

	public $attractionRepositoryAccessor;

	public function startup() {
		parent::startup();

		$this->setProperty('attractionRepositoryAccessor');
	}

	public function getAmenitiesByType($types, $limit = NULL) {

		$returnJustOneType = NULL;
		if(!is_array($types)) {
			$returnJustOneType = $types;
			$types = array($types);
		}

		$i = 0;
		$return = array();
		foreach ($this->getEntity()->getAmenities() as $amenity) {
			if(in_array($amenity->type->slug, $types)) {
				$return[$amenity->type->slug][] = $amenity;
				$i++;
			}

			if(is_numeric($limit)) {
				if($i == $limit) break;
			}
		}

		if($returnJustOneType) {
			$return = $return[$returnJustOneType];
		}

		return $return;
	}


	public function getLocationsByType($types, $limit = NULL) {

		$returnJustOneType = NULL;
		if(!is_array($types)) {
			$returnJustOneType = $types;
			$types = array($types);
		}

		$i = 0;
		$return = array();
		foreach ($this->getEntity()->getLocations() as $amenity) {
			if(in_array($amenity->type->slug, $types)) {
				$return[$amenity->type->slug][] = $amenity;
				$i++;
			}

			if(is_numeric($limit)) {
				if($i == $limit) break;
			}
		}

		if($returnJustOneType) {
			$return = $return[$returnJustOneType];
		}

		return $return;
	}

	// @todo: atrakcie sa maju vyberat na zaklade polohy objektu
	public function getAttractions($limit=30) {

		dump($this->attractionRepositoryAccessor);

	}

}
