<?php

namespace OwnerModule;

use Nette;
use Service\Seo\ISeoServiceFactory;

abstract class BasePresenter extends \SecuredPresenter {


	protected $reservationDao;


	protected $seoFactory;

	/**
	 * @autowire
	 * @var \BaseModule\Components\IHeaderControlFactory
	 */
	protected $headerControlFactory;

	/**
	 * @autowire
	 * @var \Tralandia\Reservation\Reservations
	 */
	protected $reservations;

	/**
	 * @autowire
	 * @var \Tralandia\Invoicing\InvoiceRepository
	 */
	protected $invoiceRepository;

	/**
	 * @var \Service\Seo\SeoService
	 */
	public $pageSeo;





	public function injectSeo(ISeoServiceFactory $seoFactory)
	{
		$this->seoFactory = $seoFactory;
	}

	public function injectBaseRepositories(\Nette\DI\Container $dic) {
		$this->reservationDao = $dic->getService('doctrine.default.entityManager')->getDao(RESERVATION_ENTITY);
	}

	protected function startup() {
		parent::startup();

		$this->pageSeo = $this->seoFactory->create($this->link('//this'), $this->getRequest());
	}

	protected function beforeRender() {

		$this['head']->addTitle($this->translate('o100182'));

		$this->template->currentLanguage = NULL;
		$this->template->currentLocation = NULL;

		$this->template->slogan = $this->translate('o21083').' '.$this->translate($this->environment->getPrimaryLocation()->name, NULL, array('case' => \Entity\Language::LOCATIVE));

		$this->template->envLanguage = $this->environment->getLanguage();
		$this->template->envPrimaryLocation = $this->environment->getPrimaryLocation();

		$this->template->reservationsCount = $this->reservations->getReservationsCountByUser($this->loggedUser);

//		$this->template->rental = $this->rental;

		$this->template->owner = $this->loggedUser;
		$this->template->invoices = $this->invoiceRepository->findForUser($this->loggedLeanUser);
		parent::beforeRender();
	}

	public function createComponentHeader()
	{
		return $this->headerControlFactory->create($this->pageSeo, $this->loggedUser);
	}

	public function createComponentFooter($name) {
		return $this->getService('footerControlFactory')->create($this->environment->getPrimaryLocation());
	}

	public function createComponentCountriesFooter($name) {
		//return $this->getService('countriesfooterControlFactory')->create();
	}

	public function createComponentSearchBar($name) {
		return $this->getService('searchBarControlFactory')->create($this->environment->getPrimaryLocation());
	}


}
