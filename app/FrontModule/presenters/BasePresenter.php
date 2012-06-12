<?php

namespace FrontModule;

abstract class BasePresenter extends \BasePresenter {
	
	protected function startup() {
		parent::startup();
	}

	public function beforeRender() {

		parent::beforeRender();

		$this->template->currentLanguage = $currentLanguage = $this->context->environment->getLanguage();
		$this->template->currentLocation = $currentLocation = $this->context->environment->getLocation();

		$this->template->supportedLanguages = \Service\Dictionary\LanguageList::getBySupported(\Entity\Dictionary\Language::SUPPORTED);
		$this->template->launchedCountries = \Service\Location\LocationList::getBy(
			array(
				'status'=>\Entity\Location\Location::STATUS_LAUNCHED,
				//'parentId'=>$currentLocation->findParentByType('continent')->id, // @NOTE: where parent is the current continent
			),
			null,
			15
		);
		$this->template->liveRentalsCount = count(\Service\Rental\RentalList::getByStatus(\Entity\Rental\Rental::STATUS_LIVE));

		/******* Things @TODO *****/
		$this->template->mainMenuItems = $this->getMainMenuItems();

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
