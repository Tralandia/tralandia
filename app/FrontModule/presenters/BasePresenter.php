<?php

namespace FrontModule;

abstract class BasePresenter extends \BasePresenter {
	
	protected function startup() {
		
		$this->autoCanonicalize = FALSE;
		parent::startup();

	}

	public function beforeRender() {

		parent::beforeRender();

		$this->template->supportedLanguages = $this->getSupportedLanguages();
		$this->template->mainMenuItems = $this->getMainMenuItems();

		/******* Things TODO *****/
		$this->template->allDomains = $this->getAllDomains();
		$this->template->liveRentalsCount = $this->getLiveRentalsCount();
		$this->template->currentLanguage = array("name"=>"Slovensky", "iso"=>"sk");
		$this->template->currentDomain = array("iso"=>"sk");

	}

	public function getLiveRentalsCount() {
		return 2569;
	}

	public function getSupportedLanguages() {
		return \Services\Dictionary\LanguageList::getBySupported(\Entities\Dictionary\Language::SUPPORTED);
	}

	/******* Things TODO *****/
	public function getAllDomains() {
		return array("SK", "DE", "HU");
	}

	public function getMainMenuItems() {
		return array("Uvod", "Chaty a chalupy", "Apartmany", "Uvod", "Chaty a chalupy", "Apartmany", "Uvod", "Chaty a chalupy", "Apartmany");
	}

}
