<?php

namespace Services\Location;

use Nette\Utils\Strings,
	Extras\Models\ServiceException;

class LocationService extends \Extras\Models\ServiceNested {
	
	const MAIN_ENTITY_NAME = '\Entities\Location\Location';

/*	public function setSlug($slug) {

		if(!$this->getType() instanceof \Entities\Location\Type) {
			throw new ServiceException('Pred pridanim slagu musis definovat Type locality.');
		}

		$slug = Strings::webalize(Strings::trim($slug));
		$typeSlug = $this->type->slug;
		if(in_array($typeSlug, array('region', 'city')))  { # @todo
			
		} else {
			$slugExist = LocationService::getBy('slug', $slug);
			if($slugExist) {
				$slig .= '-'.time();
			}
		}

		return $this->getMainEntity()->setSlug($slug);
	}
*/
	
}
