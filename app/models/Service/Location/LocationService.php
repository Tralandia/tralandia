<?php

namespace Service\Location;

use Service, Doctrine, Entity;
use Nette\Utils\Strings;

/**
 * @author Dávid Ďurika
 */
class LocationService extends Service\BaseService {

	protected $locationRepositoryAccessor;

	public function setLocationRepositoryAccessor($locationRepositoryAccessor) {
		$this->locationRepositoryAccessor = $locationRepositoryAccessor;
	}

	// public function generateSlug() {

	// 	if(!$this->getType() instanceof \Entity\Location\Type) {
	// 		throw new ServiceException('Pred pridanim slugu musis definovat Type locality.');
	// 	}

	// 	$slug = Strings::webalize(Strings::trim($this->name));
	// 	//if($this->getType()) @todo upravit generovanie slug-ov, ze ak jeho parentom je state, tak slug state-u pojde pred slug podriadeneho (alabama-birmingham)
	// 	$available = $this->slugIsAvailable($slug);
	// 	$i = 0;
	// 	while (!$available) {
	// 		$i++;
	// 		$available = $this->slugIsAvailable($slug.'-'.$i);
	// 	}

	// 	return $this->getMainEntity()->setSlug($i ? $slug.'-'.$i : $slug);
	// }

	// public function slugIsAvailable($slug) {
	// 	$type = $this->type;
	// 	if(in_array($type->slug, array('region', 'locality')))  {
	// 		$types = array();
	// 		$types[] = Type::getBySlug('region');
	// 		$types[] = Type::getBySlug('locality');
	// 		$locationList = LocationList::getBySlugInType($slug, $types);
	// 	} else {
	// 		$locationList = LocationList::getBySlugInType($slug, array($type));
	// 	}

	// 	if($locationList->count() > 1) {
	// 		return false;
	// 	} else if($locationList->count() == 1) {
	// 		$locationTemp = $locationList->fetch();
	// 		if($locationTemp->id == $this->id) {
	// 			return true;
	// 		} else {
	// 			return false;
	// 		}
	// 	} else {
	// 		return true;
	// 	}
	// }

	// public function findParentByType($slug = 'continent', $location = null) {

	// 	if (!$location) $location = $this;

	// 	if ($location->parent) {

	// 		$parentLocation = \Service\Location\Location::get($location->parent);

	// 		if ($parentLocation->type->slug == $slug) {

	// 			return $parentLocation;

	// 		} else {

	// 			return $this->findParentByType($slug, $parentLocation);

	// 		}
			
	// 	} else {

	// 		return $location;

	// 	}

	
}