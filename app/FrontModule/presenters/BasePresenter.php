<?php

namespace FrontModule;

abstract class BasePresenter extends \BasePresenter {
	
	protected function startup() {
		
		$this->autoCanonicalize = FALSE;
		parent::startup();

	}

	public function beforeRender() {

		parent::beforeRender();

		$this->template->supportedLanguages = \Service\Dictionary\LanguageList::getBySupported(\Entity\Dictionary\Language::SUPPORTED);
		$this->template->launchedCountries = \Service\Location\CountryList::getByStatus(\Entity\Location\Country::STATUS_LAUNCHED, null, 5);
		debug($this->template->launchedCountries);
		$this->template->liveRentalsCount = count(\Service\Rental\RentalList::getByStatus(\Entity\Rental\Rental::STATUS_LIVE));
		
		//debug($this->environment->getCountry());

		/******* Things TODO *****/
		$this->template->mainMenuItems = $this->getMainMenuItems();
		$this->template->currentLanguage = array("name"=>"Slovensky", "iso"=>"sk");
		$this->template->currentDomain = array("iso"=>"sk");

	}

	/******* Things TODO *****/
	public function getMainMenuItems() {
		return array("Uvod", "Chaty a chalupy", "Apartmany", "Uvod", "Chaty a chalupy", "Apartmany", "Uvod", "Chaty a chalupy", "Apartmany");
	}

}
