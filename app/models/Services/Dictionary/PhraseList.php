<?php

namespace Services\Dictionary;

use Extras\Models\ServiceList;

class PhraseList extends ServiceList {

	const MAIN_ENTITY_NAME = '\Entities\Dictionary\Phrase';

	public static function toTranslate() {
		$serviceList = new self;

		$qb = $serviceList->getEm()->createQueryBuilder();
		$qb->select('d, t')
			->from(self::getMainEntityName(), "d")
			->leftJoin('d.translations', "t")
			->where('t.pendingVariations IS NOT NULL');

		$serviceList->setList($qb->getQuery()->getResult());

		return $serviceList;
	}

}