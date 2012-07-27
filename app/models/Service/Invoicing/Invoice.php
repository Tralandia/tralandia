<?php

namespace Service\Invoicing;


class Invoice extends \Service\BaseService {
	
	const MAIN_ENTITY_NAME = '\Entity\Invoicing\Invoice';

	public function setExchangeRate($exchangeRate) {
		$t = self::MAIN_ENTITY_NAME;
		if($this->getMainEntity()->status >= $t::STATUS_PAID_NOT_CHECKED) {
			return false;
		}
		return $this->getMainEntity()->setExchangeRate($exchangeRate);
	}
	
}

