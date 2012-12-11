<?php

namespace Service\Rental;

use Service, Doctrine, Entity;
use Model\Medium\IMediumDecoratorFactory;
use Nette\Utils\Arrays;
/**
 * @author Dávid Ďurika
 */
class RentalService extends Service\BaseService 
{
	protected $mediumDecoratorFactory;	
	protected $rentalSearchCachingFactory;
	protected $rentalRepositoryAccessor;

	public function inject(IMediumDecoratorFactory $mediumDecoratorFactory, \Extras\Cache\IRentalSearchCachingFactory $rentalSearchCachingFactory)
	{
		$this->mediumDecoratorFactory = $mediumDecoratorFactory;
		$this->rentalSearchCachingFactory = $rentalSearchCachingFactory;
	}

	public function injectRepository(\Nette\DI\Container $dic) {
		$this->rentalRepositoryAccessor = $dic->rentalRepositoryAccessor;
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
		$images = $this->entity->getImages();
		return $images instanceof \Doctrine\Common\Collections\ArrayCollection ? $images->first() : NULL;
	}

	public function getPhotos($limit = NULL, $offset = 0) {
		return $this->entity->getImages()->slice($offset, $limit);
	}

	public function isFeatured($strict = FALSE) {
		if ($strict) {
			return (bool)$this->rentalRepositoryAccessor->get()->isFeatured($this->entity);
		} else {
			return (bool)$this->rentalSearchCachingFactory->create($this->entity->primaryLocation)->isFeatured($this->entity);
		}
	}

	public function getLocationsByType($types, $limit = NULL) {
		$returnJustOneType = NULL;
		if(!is_array($types)) {
			$returnJustOneType = $types;
			$types = array($types);
		}

		$return = array();
		$i = 0;
		foreach ($this->getEntity()->locations as $location) {
			if(!empty($location->type) && in_array($location->type->slug, $types)) {
				$return[$location->type->slug][] = $location;
				$i++;
			}

			if(is_numeric($limit)) {
				if($i == $limit) break;
			}
		}

		if($returnJustOneType) {
			
			$return = Arrays::get($return,$returnJustOneType,array());
		}

		return $return;

	}
}