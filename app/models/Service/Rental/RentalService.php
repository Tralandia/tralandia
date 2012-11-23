<?php

namespace Service\Rental;

use Service, Doctrine, Entity;
use Model\Medium\IMediumDecoratorFactory;
/**
 * @author Dávid Ďurika
 */
class RentalService extends Service\BaseService 
{
	protected $mediumDecoratorFactory;	

	public function inject(IMediumDecoratorFactory $mediumDecoratorFactory)
	{
		$this->mediumDecoratorFactory = $mediumDecoratorFactory;
	}

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

	public function getMainPhoto() {
		$photos = $this->getPhotos(1);
		return count($photos) ? reset($photos) : NULL;
	}

	public function getPhotos($limit = NULL) {
		return $this->getMediaByType('image/jpeg', $limit);
	}

	/**
	 * [getMediaByType description]
	 * @param  mixed $types string alebo pole stringov
	 * @param  int $limit
	 * @return array[\Service\Medium\MediumService] or array[$types][\Service\Medium\MediumService]
	 */
	public function getMediaByType($types, $limit = NULL) {
		$returnJustOneType = NULL;
		if(!is_array($types)) {
			$returnJustOneType = $types;
			$types = array($types);
		}

		$return = array();
		$i = 0;
		foreach ($this->getEntity()->getMedia() as $medium) {
			if(in_array($medium->type->name, $types)) {
				$return[$medium->type->name][] = $this->mediumDecoratorFactory->create($medium);
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