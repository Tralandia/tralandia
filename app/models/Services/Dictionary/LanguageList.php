<?php

namespace Services\Dictionary;

use Extras\Models\ServiceList;

class LanguageList extends ServiceList {

	public function prepareList() {
		$query = $this->getEm()->createQueryBuilder();
		$query->select('e')->from('Entities\Dictionary\Language', 'e');
		$this->list = $query->getQuery()->getResult();
	}

	public static function getBySupported($param) {
		$serviceList = new self;
		$repo = $serviceList->getEm()->getRepository('Entities\Dictionary\Language');
		$serviceList->list = $repo->findBySupported($param);
		return $serviceList;
	}
}