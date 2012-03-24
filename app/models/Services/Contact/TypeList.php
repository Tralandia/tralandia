<?php

namespace Services\Contact;

use Extras\Models\ServiceList;

class TypeList extends ServiceList {

	public function prepareList() {
		$query = $this->getEm()->createQueryBuilder();
		$query->select('e')->from('Entities\Currency', 'e');
		$this->list = $query->getQuery()->getResult();
	}


}