<?php

namespace AdminModule;

use \Services as S;

class CibiPresenter extends BasePresenter {

	public function actionDefault() {
		$currency = new S\CurrencyService;
		$currency
			->setIso(66)
			->setExchangeRate(56.0)
			->setDecimalPlaces(2)
			->setRounding(2)
			->setCreated()
			->setUpdated();
		
		debug($currency);
		$currency->save();
	}

	public function renderDefault() {

	}

}
