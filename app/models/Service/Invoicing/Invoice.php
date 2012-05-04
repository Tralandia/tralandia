<?php

namespace Service\Invoicing;


class Invoice extends \Service\BaseService {
	
	const MAIN_ENTITY_NAME = '\Entity\Invoicing\Invoice';

	public function setExchangeRate($exchangeRate) {
		if($this->getMainEntity()->status >= MAIN_ENTITY_NAME::STATUS_PAID_NOT_CHECKED) {
			return false;
		}
		return $this->getMainEntity()->setExchangeRate($exchangeRate);
	}
	
}

