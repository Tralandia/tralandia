<?php

namespace Service\Rental;

use Service, Doctrine, Entity;

/**
 * @author Dávid Ďurika
 */
class RentalService extends Service\BaseService 
{
	public function getAmenitiesByType($types, $limit = NULL)
	{
		$returnJustOneType = NULL;
		if(!is_array($types)) {
			$returnJustOneType = $types;
			$types = array($types);
		}

		$return = array();
		$i = 0;
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
}