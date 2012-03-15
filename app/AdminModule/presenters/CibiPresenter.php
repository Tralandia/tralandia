<?php

namespace AdminModule;

use \Services as S,
	\Extras\Types;

class CibiPresenter extends BasePresenter {

	public function actionDefault() {
		
		//$this->Currency();
		//$this->ChangeLog();
		$this->SystemLog();

	}

	public function renderDefault() {

	}

	private function SystemLog() {

		$changeLog = new S\Log\System\SystemLogService(1);
		$changeLog
			->setName("test")
			->setComment("test comment")
			->setDetails("detail")
			->save();

	}

	private function ChangeLog() {

		$changeLog = new S\Log\Change\ChangeLogService;
		$changeLog
			->setEntityName("test")
			->setUserEmail(new Types\Email("jan@czibula.com"))
			->setEntityId(1)
			->setDetails("[]")
			->save();

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
		
		$currency->save();

		debug($currency);
	}

}
