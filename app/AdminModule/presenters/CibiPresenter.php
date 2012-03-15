<?php

namespace AdminModule;

use \Services as S,
	\Services\Dictionary as D,
	\Extras\Types as T;

class CibiPresenter extends BasePresenter {

	public function actionDefault() {

		$dictionary = new S\Dictionary\DictionaryService;
		$dictionary->toTranslate();

	}

	private function Dictionary() {
/*
		$language = new D\LanguageService(3);
		$language->setName(new D\PhraseService(3))->save();
	
		$lang = array(
			1 => array(
					1 => "English",
					2 => "Anglicky",
					3 => "Anglicky",
				),
			2 => array(
					1 => "Slovak",
					2 => "Slovensky",
					3 => "Slovensky",
				),
			3 => array(
					1 => "Hello World!",
					2 => "Ahoj svet!",
					3 => "Ahoj světe!",
				),
			4 => array(
					1 => "Login",
					2 => "Prihlásiť",
					3 => "Přihlášení",
				),
			5 => array(
					1 => "Logout",
					2 => "Odhlásiť",
					3 => "Odhlásit",
				),
			6 => array(
					1 => "Home",
					2 => "Domov",
					3 => "Domu",
				),
			7 => array(
					1 => "Phrase",
					2 => "Fráza",
					3 => "Fráze",
				),
		);

		foreach ($lang as $p => $value) {

			foreach ($value as $language => $string) {

				$translation = new D\TranslationService();
				$translation->language = new D\LanguageService($language);
				$translation->translation = $string;
				$translation->translated = new T\Datetime;
				$translation->variations = new T\Json("[]");
				$translation->variationsPending = new T\Json("[]");
				$translation->save();

				$phrase = new D\PhraseService($p);
				$phrase->addTranslation($translation);
				$phrase->type = new D\TypeService(1);
				$phrase->ready = true;
			    $phrase->entityId = 2;
			    $phrase->details = new T\Json("[]");
			    $phrase->save();

			}

		}

/*

		$phrase = new D\PhraseService(8);
		$phrase->addTranslation(new D\TranslationService(1));
		$phrase->type = new D\TypeService(1);
		$phrase->ready = false;
	    $phrase->entityId = 2;
	    $phrase->details = new \Extras\Types\Json("[]");
	    $phrase->save();

		$type = new D\TypeService;
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

		$language = new D\LanguageService;
		$language
			//->setName(new D\PhraseService(1))
		    ->setIso("sk")
		    ->setSupported(true)
		    ->setDefaultCollation("Slovak")
		    ->setSalutations(new \Extras\Types\Json("[]"))
		    ->setMultitranslationOptions(new \Extras\Types\Json("[]"))
		    ->setGenderNumberOptions(new \Extras\Types\Json("[]"))
		    ->setPpcPatterns(new \Extras\Types\Json("[]"))
		    //->setLocations()
		    //->setRentals()
		    ->setDetails(new \Extras\Types\Json("[]"))
			->save();

		$translation = new D\TranslationService;
		$translation
			->setLanguage(new D\LanguageService(2))
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
