<?php

namespace FrontModule;

use Nette;

abstract class BasePresenter extends \BasePresenter {
	
	protected $languageRepositoryAccessor;
	protected $locationRepositoryAccessor;
	/**
	 * @var \Extras\Models\Repository\RepositoryAccessor
	 */
	protected $rentalTypeRepositoryAccessor;

	/**
	 * @var \Extras\Models\Repository\RepositoryAccessor
	 */
	protected $rentalRepositoryAccessor;

	/**
	 * @autowire
	 * @var \Extras\Environment
	 */
	protected $environment;

	/**
	 * @autowire
	 * @var \Service\Seo\ISeoServiceFactory
	 */
	protected $seoFactory;


	/**
	 * @var \Service\Seo\SeoService
	 */
	public $pageSeo;

	public function injectBaseRepositories(\Nette\DI\Container $dic) {
		$this->languageRepositoryAccessor = $dic->languageRepositoryAccessor;
		$this->locationRepositoryAccessor = $dic->locationRepositoryAccessor;
		$this->rentalTypeRepositoryAccessor = $dic->rentalTypeRepositoryAccessor;
		$this->rentalRepositoryAccessor = $dic->rentalRepositoryAccessor;
	}

	protected function startup() {
		parent::startup();

		$this->pageSeo = $this->seoFactory->create($this->link('//this'), $this->getRequest());
	}


	public function beforeRender() {

		$this->template->currentLanguage = NULL;
		$this->template->currentLocation = NULL;

		// $this->template->supportedLanguages = $this->languageRepositoryAccessor->findBySupported(\Entity\Language::SUPPORTED);
		// $this->template->launchedCountries = $this->locationRepositoryAccessor->findBy(array('status'=>\Entity\Location\Location::STATUS_LAUNCHED), null, 15);
		// $this->template->liveRentalsCount = count($this->rentalRepositoryAccessor->findByStatus(\Entity\Rental\Rental::STATUS_LIVE));

		
		$this->template->mainMenuItems = $this->rentalTypeRepositoryAccessor->get()->findBy(array(),NULL,8);

		$this->template->slogan = $this->translate('o21083').' '.$this->translate($this->environment->getPrimaryLocation()->getName() , NULL, array('case' => \Entity\Language::LOCATIVE));


		$this->template->envLanguage = $this->environment->getLanguage();
		$this->template->envPrimaryLocation = $this->environment->getPrimaryLocation();

		$supportedLanguages = $this->languageRepositoryAccessor->get()->findSupported();
		$this->template->supportedLanguages = array_chunk($supportedLanguages,round(count($supportedLanguages)/3));

		$this->template->pageH1 = $this->pageSeo->getH1();
		$this->template->pageTitle = $this->pageSeo->getTitle();
		$this->template->countryCountObjects =  $this->environment->primaryLocation->rentalCount;
		//d($this->environment);

		$this->template->worldwideCount = $this->locationRepositoryAccessor->get()->getWorldwideRentalCount();
		
		parent::beforeRender();
	}


	public function createComponentBreadcrumb($name) {
		
//		return new \FrontModule\Breadcrumb\Breadcrumb($this, $name);
	}

	public function createComponentFooter($name) {
		return $this->getService('footerControlFactory')->create($this->environment->getPrimaryLocation());
	}

	public function createComponentCountriesFooter($name) {
		return $this->getService('countriesFooterControlFactory')->create();
	}

	public function createComponentSearchBar($name) {
		return $this->getService('searchBarControlFactory')->create($this->environment->getPrimaryLocation());
	}

}
