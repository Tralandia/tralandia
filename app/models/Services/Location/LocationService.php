<?php

namespace Services\Location;

use Nette\Utils\Strings,
	Extras\Models\ServiceException;

class LocationService extends \Extras\Models\ServiceNested {
	
	const MAIN_ENTITY_NAME = '\Entities\Location\Location';

	public function setSlug($slug) {

		if(!$this->getType() instanceof \Entities\Location\Type) {
			throw new ServiceException('Pred pridanim slagu musis definovat Type locality.');
		}

		$slug = Strings::webalize(Strings::trim($slug));
		$type = $this->type;
		if(in_array($type->slug, array('region', 'city')))  { # @todo
			$types = array();
			$types[] = TypeService::getBySlug('region');
			$types[] = TypeService::getBySlug('city');
			$locationList = S\Location\LocationList::getBySlugInTypes($slug, $types);
		} else {
			$locationList = S\Location\LocationList::getBySlugInTypes($slug, array($type));
		}
		if($locationList->count()) {
			$slug .= '-'.time();
		}

		return $this->getMainEntity()->setSlug($slug);
	}

	
}
