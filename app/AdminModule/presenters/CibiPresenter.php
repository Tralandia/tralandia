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

/*
		$type = new S\Dictionary\TypeService;
		$type
			->setName("test")
			->setEntityName("test")
			->setEntityAttribute("test")
			->setTranslationLevelRequirement(1)
			->setMultitranslationRequired(1)
			->setGenderNumberRequired(1)
			->setLocativeRequired(1)
			->setPositionRequired(1)
			->setWebalizedRequired(1)
			->save();
*/

		$language = new S\Dictionary\LanguageService;
		$language
			->setName(new S\Dictionary\PhraseService(1))
		    ->setIso("en")
		    ->setSupported(true)
		    ->setDefaultCollation("en")
		    ->setSalutations("[]")
		    ->setMultitranslationOptions()
		    ->setGenderNumberOptions("[]")
		    ->setPpcPatterns("[]")
		    //->setLocations()
		    //->setRentals()
		    ->setDetails("[]")
			->save();

/*
		$translation = new \Entities\Dictionary\Translation;
		$translation
			->setPhrase(new S\Dictionary\PhraseService(2))
			->setLanguage()
			->setTranslation()
			->setTranslation2()
			->setTranslation3()
			->setTranslation4()
			->setTranslation5()
			->setTranslation6()
			->setTranslationWebalized()
			->setTranslationWebalized2()
			->setTranslationWebalized3()
			->setTranslationWebalized4()
			->setTranslationWebalized5()
			->setTranslationWebalized6()
			->setTranslationPending()
			->setTranslationPending2()
			->setTranslationPending3()
			->setTranslationPending4()
			->setTranslationPending5()
			->setTranslationPending6()
			->setTranslated()
			->setVariations()
			->setVariationsPending();

		debug($translation);
		return false;
*/
/*
		$phrase = new S\Dictionary\PhraseService;
		$phrase
			->setType(new S\Dictionary\TypeService(1))
			->setReady(false)
	    	->setEntityId(1)
	    	->setDetails("[]")
	    	->save();
*/		
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
