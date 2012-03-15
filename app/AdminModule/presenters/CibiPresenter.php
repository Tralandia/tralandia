<?php

namespace AdminModule;

use \Services as S,
	\Extras\Types;

class CibiPresenter extends BasePresenter {

	public function actionDefault() {
		
		$d = new S\Dictionary\DictionaryService;

		$this->Dictionary();

	}

	private function Dictionary() {

		$translation = new S\Dictionary\TranslationService(1);

		$phrase = new S\Dictionary\PhraseService(1);
		$phrase
			->addTranslation($translation)
			->setType(new S\Dictionary\TypeService(1))
			->setReady(false)
	    	->setEntityId(2)
	    	->setDetails(new \Extras\Types\Json("[]"))
	    	->save();
	    $translation->save();
	    debug($phrase);

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

		$language = new S\Dictionary\LanguageService(1);
		$language
			->setName(new S\Dictionary\PhraseService(1))
		    ->setIso("en")
		    ->setSupported(true)
		    ->setDefaultCollation("English")
		    ->setSalutations(new \Extras\Types\Json("[]"))
		    ->setMultitranslationOptions(new \Extras\Types\Json("[]"))
		    ->setGenderNumberOptions(new \Extras\Types\Json("[]"))
		    ->setPpcPatterns(new \Extras\Types\Json("[]"))
		    //->setLocations()
		    //->setRentals()
		    ->setDetails(new \Extras\Types\Json("[]"))
			->save();

		$translation = new S\Dictionary\TranslationService(1);
		$translation
			->setLanguage(new S\Dictionary\LanguageService(1))
			->setTranslation("Home")
			->setTranslationWebalized("Preklad Webalized")
			->setTranslationPending("Preklad pending")
			->setTranslated(new \Extras\Types\Datetime)
			->setVariations(new \Extras\Types\Json("[]"))
			->setVariationsPending(new \Extras\Types\Json("[]"))
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
