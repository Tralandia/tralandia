<?php

namespace FrontModule;

abstract class BasePresenter extends \BasePresenter {
	
	public $languageRepositoryAccessor;
	public $locationRepositoryAccessor;
	public $rentalRepositoryAccessor;

	public function setContext(\Nette\DI\Container $dic) {

		$this->setProperty('languageRepositoryAccessor');
		$this->setProperty('locationRepositoryAccessor');
		$this->setProperty('rentalRepositoryAccessor');
		parent::setContext($dic);
	}

	public function beforeRender() {

		$this->template->currentLanguage = NULL;
		$this->template->currentLocation = NULL;

		// $this->template->supportedLanguages = $this->languageRepositoryAccessor->findBySupported(\Entity\Language::SUPPORTED);
		// $this->template->launchedCountries = $this->locationRepositoryAccessor->findBy(array('status'=>\Entity\Location\Location::STATUS_LAUNCHED), null, 15);
		// $this->template->liveRentalsCount = count($this->rentalRepositoryAccessor->findByStatus(\Entity\Rental\Rental::STATUS_LIVE));
		$this->template->mainMenuItems = $this->getMainMenuItems();

		parent::beforeRender();
	}

	/******* Things @TODO *****/
	public function getMainMenuItems() {

		return array("Uvod", "Chaty a chalupy", "Apartmany", "Uvod", "Chaty a chalupy", "Apartmany", "Uvod", "Chaty a chalupy", "Apartmany");
	}

	public function createComponentMainMenu($name) {

		return new \FrontModule\MainMenu\MainMenu($this, $name);
	}

	public function createComponentBreadcrumb($name) {
		
		return new \FrontModule\Breadcrumb\Breadcrumb($this, $name);
	}


}
