<?php

namespace FrontModule;

abstract class BasePresenter extends \BasePresenter {
	
	public $languageRepositoryAccessor;
	public $locationRepositoryAccessor;
	public $locationTypeRepositoryAccessor;
	public $rentalRepositoryAccessor;

	public function inject(\Nette\DI\Container $dic) {
		$this->setProperty('languageRepositoryAccessor');
		$this->setProperty('locationRepositoryAccessor');
		$this->setProperty('locationTypeRepositoryAccessor');
		$this->setProperty('rentalRepositoryAccessor');
		parent::inject($dic);
	}

	public function beforeRender() {

		$this->template->currentLanguage = NULL;
		$this->template->currentLocation = NULL;

		// $this->template->supportedLanguages = $this->languageRepositoryAccessor->findBySupported(\Entity\Language::SUPPORTED);
		// $this->template->launchedCountries = $this->locationRepositoryAccessor->findBy(array('status'=>\Entity\Location\Location::STATUS_LAUNCHED), null, 15);
		// $this->template->liveRentalsCount = count($this->rentalRepositoryAccessor->findByStatus(\Entity\Rental\Rental::STATUS_LIVE));
		$this->template->mainMenuItems = $this->locationTypeRepositoryAccessor->get()->findBy(array(),null,8);

		parent::beforeRender();
	}


	public function createComponentBreadcrumb($name) {
		
		return new \FrontModule\Breadcrumb\Breadcrumb($this, $name);
	}


}
