<?php

namespace AdminModule;

use \Services as S;

class CibiPresenter extends BasePresenter {

	public function actionDefault() {
		
		//$this->Currency();

	}

	public function renderDefault() {

	}

	private function Currency() {

		$c = new S\CurrencyService(2);
		$c->setIso(33)->save();
		debug($c);
		
		$currency = new S\CurrencyService;
		$currency
			->setIso(66)
			->setExchangeRate(56.0)
			->setDecimalPlaces(2)
			->setRounding(2);
		
		debug($currency);
		$currency->save();

	}

}
