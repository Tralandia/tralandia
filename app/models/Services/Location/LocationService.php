<?php

namespace Services\Location;

use Extras\Models\ServiceException;

class LocationService extends \Extras\Models\ServiceNested {
	
	const MAIN_ENTITY_NAME = '\Entities\Location\Location';

	public function setSlug($slug) {
		if(! $this->getType() instanceof \Entities\Location\Type) {
			throw new ServiceException('Pred pridanim slagu musis definovat Type locality.');
					
		}
		return $this->getMainEntity()->setSlug($slug);
	}
	
}
