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
		if(in_array($type->slug, array('region', 'locality')))  {
			$types = array();
			$types[] = Type::getBySlug('region');
			$types[] = Type::getBySlug('locality');
			$locationList = LocationList::getBySlugInType($slug, $types);
		} else {
			$locationList = LocationList::getBySlugInType($slug, array($type));
		}
		return $locationList->count() > 1 ? FALSE : TRUE; # @fix vracia false lebo najde seba sameho

	}

	public function getContinent() {

		if (!$this->parentId) return null;

		$parent = \Service\Location\Location::get($this->parentId);
		return ($parent->type->slug=='continent') ? $parent : self::getContinent($parent);

	}

	public function getRentalsCount($id) {

		// $serviceList = new static;

		// $qb = $serviceList->getEm()->createQueryBuilder();

		// $qb->select('r')
		// 	->from('\Entity\Rental\Rental', 'r')
		// 	->where($qb->expr()->in('r.locations', \Service\Location\Location::get($id)));
		// 	// ->andWhere($qb->expr()->in('e.'.$nameIn, $parsedIn))
		// 	// ->setParameter('by', \Service\Location\Location::get($id));

		// return $qb->getQuery()->getResult();

	}
	
}
