<?php

namespace Service\Location;

use Nette\Utils\Strings,
	Extras\Models\ServiceException;

class Location extends \Extras\Models\ServiceNested {
	
	const MAIN_ENTITY_NAME = '\Entity\Location\Location';

	public function setSlug($slug) {

		if(!$this->getType() instanceof \Entity\Location\Type) {
			throw new ServiceException('Pred pridanim slagu musis definovat Type locality.');
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
		if(in_array($type->slug, array('region', 'locality')))  { # @todo
			$types = array();
			$types[] = TypeService::getBySlug('region');
			$types[] = TypeService::getBySlug('locality');
			$locationList = LocationList::getBySlugInType($slug, $types);
		} else {
			$locationList = LocationList::getBySlugInType($slug, array($type));
		}
		return $locationList->count() ? FALSE : TRUE;

	}

	
}
