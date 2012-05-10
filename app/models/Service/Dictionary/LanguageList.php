<?php

namespace Service\Dictionary;

use Extras\Models\ServiceList;

class LanguageList extends ServiceList {

	const MAIN_ENTITY_NAME = '\Entity\Dictionary\Language';

	public static function getGridList() {
		$entityName = static::getMainEntityName();

		$serviceList = new static;

		$qb = $serviceList->getEm()->createQueryBuilder();
		$qb->select('e', 'LENGTH(e.defaultCollation) as test')
			->from($entityName, 'e');


		$serviceList->setDataSource($qb);

		return $serviceList;

	}

}