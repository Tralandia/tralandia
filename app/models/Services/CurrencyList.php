<?php

namespace Services;

use Extras\Models\ServiceList;

class CurrencyList extends ServiceList {

	public function prepareList() {
		$query = $this->getEm()->createQueryBuilder();
		$query->select('e')->from('Entities\Currency', 'e');
		$this->list = $query->getQuery()->getResult();
	}
}