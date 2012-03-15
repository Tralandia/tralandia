<?php

namespace AdminModule;

use \Services as S,
	\Extras\Types;

class CibiPresenter extends BasePresenter {

	public function actionDefault() {
		
		//$this->Currency();
		//$this->ChangeLog();
		//$this->SystemLog();
		$this->Dictionary();

	}

	private function Dictionary() {

		$d = new S\Dictionary\PhraseService;
		$d	->setTranslations(new \Entities\Dictionary\Translation)
			->setReady(true)
	    	->setType(new \Entities\Dictionary\Type)
	    	->setEntityId(1)
	    	->setDetails("[]")
	    	->save();
		debug($d);
		
	} 

	private function SystemLog() {

		$sLog = new S\Log\System\SystemLogService;
		$sLog
			->setName("test")
			->setComment("test comment2")
			->setDetails("[]")
			->save();

	}

	private function ChangeLog() {

		$cLog = new S\Log\Change\ChangeLogService;
		$cLog
			->setEntityName("test")
			->setUserEmail(new Types\Email("jan@czibula.com"))
			->setEntityId(1)
			->setDetails("[]")
			->save();

	}

	private function Currency() {
		
		$currency = new S\CurrencyService;
		$currency
			->setIso(66)
			->setExchangeRate(56.0)
			->setDecimalPlaces(2)
			->setRounding(2)
			->save();

	}

}
