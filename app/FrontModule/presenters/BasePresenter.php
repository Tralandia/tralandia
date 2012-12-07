<?php

namespace FrontModule;

use Nette;
use Service\Seo\ISeoServiceFactory;

abstract class BasePresenter extends \BasePresenter {
	
	public $languageRepositoryAccessor;
	public $locationRepositoryAccessor;
	public $rentalTypeRepositoryAccessor;
	public $rentalRepositoryAccessor;
	
	protected $environment;
	protected $seoFactory;

	public function injectSeo(ISeoServiceFactory $seoFactory)
	{
		$this->seoFactory = $seoFactory;
	}

	public function injectEnvironment(\Extras\Environment $environment) 
	{
		$this->environment = $environment;
	}

	public function inject(\Nette\DI\Container $dic) {
		$this->setProperty('languageRepositoryAccessor');
		$this->setProperty('locationRepositoryAccessor');
		$this->setProperty('rentalTypeRepositoryAccessor');
		$this->setProperty('rentalRepositoryAccessor');
		parent::inject($dic);
	}

	public function beforeRender() {

		$this->template->currentLanguage = NULL;
		$this->template->currentLocation = NULL;

		// $this->template->supportedLanguages = $this->languageRepositoryAccessor->findBySupported(\Entity\Language::SUPPORTED);
		// $this->template->launchedCountries = $this->locationRepositoryAccessor->findBy(array('status'=>\Entity\Location\Location::STATUS_LAUNCHED), null, 15);
		// $this->template->liveRentalsCount = count($this->rentalRepositoryAccessor->findByStatus(\Entity\Rental\Rental::STATUS_LIVE));
		$this->template->mainMenuItems = $this->rentalTypeRepositoryAccessor->get()->findBy(array(),null,8);
		$this->template->slogan = $this->translate('o21083').' '.$this->translate($this->environment->getPrimaryLocation()->name, NULL, array('case' => \Entity\Language::LOCATIVE));

		$this->template->envLanguage = $this->environment->getLanguage();
		$this->template->envPrimaryLocation = $this->environment->getPrimaryLocation();
		parent::beforeRender();
	}


	public function createComponentBreadcrumb($name) {
		
		return new \FrontModule\Breadcrumb\Breadcrumb($this, $name);
	}

	public function createComponentFooter($name) {
		return $this->getService('footerControlFactory')->create($this->environment->getPrimaryLocation());
	}

	public function createComponentCountriesFooter($name) {
		return $this->getService('countriesfooterControlFactory')->create();
	}

	public function createComponentSearchBar($name) {
		return $this->getService('searchBarControlFactory')->create($this->environment->getPrimaryLocation());
	}

}
