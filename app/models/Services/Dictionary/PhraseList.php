<?php

namespace Services\Dictionary;

use Extras\Models\ServiceList;

class PhraseList extends ServiceList {


	public static function toTranslate() {
		$serviceList = new self;

		$qb = $serviceList->getEm()->createQueryBuilder();
		$qb->select('d, t')
			->from('\Entities\Dictionary\Phrase', "d")
			->leftJoin('d.translations', "t")
			->where('t.pendingVariations IS NOT NULL');

		$serviceList->list = ($qb->getQuery()->getResult());

		return $serviceList;
	}

}