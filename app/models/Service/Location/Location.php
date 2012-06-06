<?php

namespace Service\Location;

use Nette\Utils\Strings,
	Extras\Models\ServiceException;

class Location extends \Extras\Models\Service {
	
	const MAIN_ENTITY_NAME = '\Entity\Location\Location';

	public function setSlug($slug) {

		if(!$this->getType() instanceof \Entity\Location\Type) {
			throw new ServiceException('Pred pridanim slugu musis definovat Type locality.');
		}

		$slug = Strings::webalize(Strings::trim($slug));
		$available = $this->slugIsAvailable($slug);
		$i = 0;
		while (!$available) {
			$i++;
			$available = $this->slugIsAvailable($slug.'-'.$i);
		}

		return $this->getMainEntity()->setSlug($i ? $slug.'-'.$i : $slug);
	}

	public function slugIsAvailable($slug) {
		$type = $this->type;
		if(in_array($type->slug, array('region', 'locality')))  {
			$types = array();
			$types[] = Type::getBySlug('region');
			$types[] = Type::getBySlug('locality');
			$locationList = LocationList::getBySlugInType($slug, $types);
		} else {
			$locationList = LocationList::getBySlugInType($slug, array($type));
		}
		return $locationList->count() > 1 ? FALSE : TRUE; # @todo @fix vracia false lebo najde seba sameho

	}

	public function findParentByType($slug = 'continent', $location = null) {

		if (!$location) $location = $this;

		if ($location->parentId) {

			$parentLocation = \Service\Location\Location::get($location->parentId);

			if ($parentLocation->type->slug == $slug) {

				return $parentLocation;

			} else {

				return $this->findParentByType($slug, $parentLocation);

			}
			
		} else {

			return $location;

		}

	}

}
