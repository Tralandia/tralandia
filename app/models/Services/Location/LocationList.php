<?php

namespace Services\Location;

use Extras\Models\ServiceList;

class LocationList extends ServiceList {

	const MAIN_ENTITY_NAME = '\Entities\Location\Location';


	public static function getBySlugInTypes($slug, array $types) {
		$typesIds = array();
		foreach ($types as $key => $value) {
			$typesIds[] = $value->id;
		}

		$serviceList = new static;

		$qb = $serviceList->getEm()->createQueryBuilder();
		$qb->select('l')
			->from(self::getMainEntityName(), 'l')
			->where($qb->expr()->in('l.type', $typesIds))
			->andWhere('l.slug = :slug')
			->setParameter('slug', $slug);
		$serviceList->setList($qb->getQuery()->getResult());

		return $serviceList;

	}
}