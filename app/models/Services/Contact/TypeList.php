<?php

namespace Services\Contact;

use Extras\Models\ServiceList;

class TypeList extends ServiceList {

	public function prepareList() {
		$query = $this->getEm()->createQueryBuilder();
		$query->select('e')->from('Entities\Currency', 'e');
		$this->list = $query->getQuery()->getResult();
	}

	public function getByClass($class) {
		$query = $this->getEm()->createQueryBuilder();
		$query->select('e')->from('Entities\Contact\Type', 'e')->where('class = ?1', $class);
		$this->list = $query->getQuery()->getResult();
	}
}