<?php

namespace FrontModule;

abstract class BasePresenter extends \BasePresenter {
	
	protected function startup() {
		
		$this->autoCanonicalize = FALSE;
		parent::startup();

	}

	public function beforeRender() {

		parent::beforeRender();

		$this->template->supportedLanguages = \Services\Dictionary\LanguageList::getBySupported(\Entities\Dictionary\Language::SUPPORTED);

		/******* Things TODO *****/
		$this->template->allDomains = $this->getAllDomains(); //\Services\DomainList::getAll();
		$this->template->mainMenuItems = $this->getMainMenuItems();
		$this->template->liveRentalsCount = 2569; //count(\Services\Rental\RentalList::getByStatus(\Entities\Rental\Rental::STATUS_LIVE));
		$this->template->currentLanguage = array("name"=>"Slovensky", "iso"=>"sk");
		$this->template->currentDomain = array("iso"=>"sk");

	}

	/******* Things TODO *****/
	public function getAllDomains() {
		return array("SK", "DE", "HU");
	}

	public function getMainMenuItems() {
		return array("Uvod", "Chaty a chalupy", "Apartmany", "Uvod", "Chaty a chalupy", "Apartmany", "Uvod", "Chaty a chalupy", "Apartmany");
	}

}
