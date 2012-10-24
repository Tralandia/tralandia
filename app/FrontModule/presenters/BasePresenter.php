<?php

namespace FrontModule;

abstract class BasePresenter extends \BasePresenter {
	
	public $languageRepository;
	public $locationRepository;
	public $rentalRepository;

	protected function startup() {

		parent::startup();
	}

	public function setContext() {

		$this->languageRepository = $this->context->languageRepository;
		$this->locationRepository = $this->context->locationRepository;
		$this->rentalRepository = $this->context->rentalRepository;

		parent::setContext();
	}

	public function beforeRender() {

		$this->template->currentLanguage = NULL;
		$this->template->currentLocation = NULL;

		$this->template->supportedLanguages = $this->languageRepository->findBySupported(\Entity\Language::SUPPORTED);
		$this->template->launchedCountries = $this->locationRepository->findBy(array('status'=>\Entity\Location\Location::STATUS_LAUNCHED), null, 15);
		$this->template->liveRentalsCount = count($this->rentalRepository->findByStatus(\Entity\Rental\Rental::STATUS_LIVE));
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
