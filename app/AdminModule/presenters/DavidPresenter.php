<?php

namespace AdminModule;

use Nette\Application as NA,
	Nette\Environment,
	Nette\Diagnostics\Debugger,
	Nette\Utils\Html,
	Nette\Utils\Strings,
	Tra\Services\Dictionary as D,
	Tra\Services as S;

class DavidPresenter extends BasePresenter {

	public function beforeRender() {
		parent::beforeRender();
		$this->setView('list');
	}

	public function actionTest() {
		$phrase = new D\PhraseService(1);
		$translation = $phrase->getTranslation(new D\LanguageService(1));
		debug($translation);
	}

	public function actionAddPhrase() {
		$dictionary = new D\DictionaryService;

		$type = $dictionary->getType(1);
		$phrase = $dictionary->createPhrase(array(
			'type' => $type,
			'ready' => FALSE,
			'entityId' => FALSE,
		));
		debug($phrase);
	}

	public function actionUpdatePhrase($id) {
		$phrase = new D\PhraseService($id);
		debug($phrase);

		$l1 = new D\LanguageService(3);
		$l2 = new D\LanguageService(1);
		$phrase->addLanguage($l1)->addLanguage($l2);

		$language = new D\LanguageService(2);
		$phrase->removeLanguage($language);

		$phrase->createTranslation(array(
			'language' => $l1,
			'translation' => 'Slovensky',
		));

		$phrase->createTranslation(array(
			'language' => $l2,
			'translation' => 'Slovak',
		));

		$phrase->save();
		debug($phrase);
	}

	public function actionUpdateLanguage($id) {
		$translation = new D\LanguageService($id);
		$translation->name = new D\PhraseService(1);
		$translation->save();
		debug($translation);
	}

	public function actionAddLanguage($id) {
		$language = new D\LanguageService;
		$language->iso = $id;
		$language->supported = false;
		$language->save();
		debug($language);
	}

	public function actionList() {

		$dictionary = new D\DictionaryService;

		$quality = $dictionary->createQuality('basic3', 12);
		debug($quality);

		$type = $dictionary->createType(array(
			'entityName' => 'rental',
			'entityAttribute' => 'name',
			'translationQualityRequirement' => $quality, 
			'isMultitranslationRequired' => FALSE, 
			'isGenderNumberRequired' => FALSE, 
			'isLocativeRequired' => FALSE, 
			'isPositionRequired' => FALSE, 
			'isWebalizedRequired' => FALSE, 
		));
		debug($type);
		debug($dictionary);

		/*
		$country = new \Tra\Services\Country(1);
		$country->iso = 'sk';
		$country->save();


		$user = new \Tra\Services\User;
		$user->country = $country;
		$user->login = 'waccoTEST';
		$user->active = true;
		$user->password = 'testik';
		$user->save();
		*/
	}
	
	public function renderAdd() {
		$this->template->form = $this->getComponent('form');
	}

	protected function createComponentForm($name) {
		return new \Tra\Forms\User($this, $name);
	}

}
