<?php

namespace AdminModule;

use Nette\Application as NA,
	Nette\Environment,
	Nette\Diagnostics\Debugger,
	Nette\Utils\Html,
	Nette\Utils\Strings,
	Tra\Services as S;

class DavidPresenter extends BasePresenter {

	public function beforeRender() {
		parent::beforeRender();
		$this->setView('list');
	}


	public function actionAddPhrase() {
		$dictionary = new S\DictionaryService;

		$type = $dictionary->getType(3);
		$phrase = $dictionary->addPhrase(array(
			'type' => $type,
			'ready' => FALSE,
			'entityId' => FALSE,
		));
		debug($phrase);
	}

	public function actionUpdatePhrase($id) {
		$phrase = new S\PhraseService($id);
		debug($phrase->languages);

		$language = new S\LanguageService(1);
		$language = new S\LanguageService(2);

		$phrase->addLanguage($language);
		$phrase->removeLanguage($language);
		$phrase->save();
		debug($phrase->languages);
	}

	public function actionAddLanguage() {
		$language = new S\LanguageService;
		$language->iso = 'gr';
		$language->supported = false;
		$language->save();
		debug($language);
	}

	public function actionList() {

		$dictionary = new S\DictionaryService;

		$quality = $dictionary->addQuality('basic3', 12);
		debug($quality);

		$type = $dictionary->addType(array(
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
