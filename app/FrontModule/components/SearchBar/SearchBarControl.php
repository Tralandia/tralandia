<?php 
namespace FrontModule\Components\SearchBar;

use Doctrine\ORM\EntityManager;
use FrontModule\Forms\ISearchFormFactory;
use SearchGenerator\OptionGenerator;
use Service\Rental\RentalSearchService;

class SearchBarControl extends \BaseModule\Components\BaseControl {

	/**
	 * @persistent
	 * @var string
	 */
	public $rentalType;

	/**
	 * @persistent
	 * @var string
	 */
	public $location;

	/**
	 * @persistent
	 * @var string
	 */
	public $priceFrom;

	/**
	 * @persistent
	 * @var string
	 */
	public $priceTo;

	/**
	 * @persistent
	 * @var string
	 */
	public $capacity;

	/**
	 * @persistent
	 * @var string
	 */
	public $spokenLanguage;

	/**
	 * @var \Service\Rental\RentalSearchService
	 */
	protected $search;

	/**
	 * @var \Doctrine\ORM\EntityManager
	 */
	protected $em;

	/**
	 * @var OptionGenerator
	 */
	protected $searchOptionGenerator;

	/**
	 * @autowire
	 * @var \FrontModule\Forms\ISearchFormFactory
	 */
	protected $searchFormFactory;

	/**
	 * @param \Service\Rental\RentalSearchService $search
	 * @param \Doctrine\ORM\EntityManager $em
	 * @param ISearchFormFactory $searchFormFactory
	 * @param \SearchGenerator\OptionGenerator $searchOptionGenerator
	 */
	public function __construct(RentalSearchService $search,EntityManager $em,
								ISearchFormFactory $searchFormFactory, OptionGenerator $searchOptionGenerator)
	{
		parent::__construct();
		$this->search = $search;
		$this->em = $em;
		$this->searchFormFactory = $searchFormFactory;
		$this->searchOptionGenerator = $searchOptionGenerator;
	}

	public function render()
	{
		$template = $this->template;

		if(!$this->location) {
			$locations = $this->searchOptionGenerator->generateLocationLinks();
			$template->locations = $locations;
		} else if(!$this->rentalType) {
			$rentalTypes = $this->searchOptionGenerator->generateRentalTypeLinks();
			$template->rentalTypes = $rentalTypes;
		}

		if($this->location) {
			$template->removeLocation = $this->link('this', ['location' => NULL]);
		}

		if($this->rentalType) {
			$template->removeRentalType = $this->link('this', ['rentalType' => NULL]);
		}

		if($this->priceFrom) {
			$template->removePriceFrom = $this->link('this', ['priceFrom' => NULL]);
		}

		if($this->priceTo) {
			$template->removePriceTo = $this->link('this', ['priceTo' => NULL]);
		}

		if($this->capacity) {
			$template->removeCapacity = $this->link('this', ['capacity' => NULL]);
		}

		if($this->spokenLanguage) {
			$template->removeSpokenLanguage = $this->link('this', ['spokenLanguage' => NULL]);
		}

		$search = $this->getSearch();

		$template->totalResultsCount = $search->getRentalsCount();

		$template->render();
	}

	public function handleGetSearchCount()
	{
		$em = $this->em;
		$presenter = $this->getPresenter();

		$location = $presenter->getParameter('location');
		if($location) {
			$this->location = $em->getRepository(LOCATION_ENTITY)->find($location);
		}

		$rentalType = $presenter->getParameter('rentalType');
		if($rentalType) {
			$this->rentalType = $em->getRepository(LOCATION_ENTITY)->find($rentalType);
		}

		$this->priceFrom = $presenter->getParameter('priceFrom', NULL);

		$this->priceTo = $presenter->getParameter('priceTo', NULL);

		$this->capacity = $presenter->getParameter('capacity', NULL);

		$spokenLanguage = $presenter->getParameter('spokenLanguage');
		if($spokenLanguage) {
			$this->spokenLanguage = $em->getRepository(LANGUAGE_ENTITY)->find($spokenLanguage);
		}

		$search = $this->getSearch();

		$count = $search->getRentalsCount();

		$this->presenter->sendJson(['count' => $count]);
	}

	/**
	 * @return \Service\Rental\RentalSearchService
	 */
	public function getSearch()
	{
		$search = $this->search;

		if ($this->location) {
			$search->setLocationCriterium($this->location);
		}

		if ($this->rentalType) {
			$search->setRentalTypeCriterium($this->rentalType);
		}

		return $search;
	}

	/**
	 * @return \FrontModule\Forms\SearchForm
	 */
	protected function createComponentSearchForm()
	{
		$form = $this->searchFormFactory->create();
		//$form->buildForm();

		$form->onSuccess[] = function ($form) {
			$values = $form->getValues();
			$form->presenter->redirect('RentalList', (array) $values);
		};

		if($this->location) {
			$form['location']->setDefaultValue($this->location->getId());
		}

		if($this->rentalType) {
			$form['rentalType']->setDefaultValue($this->rentalType->getId());
		}

		if($this->priceFrom) {
			$form['priceFrom']->setDefaultValue($this->priceFrom);
		}

		if($this->priceTo) {
			$form['priceTo']->setDefaultValue($this->priceTo);
		}

		if($this->capacity) {
			$form['capacity']->setDefaultValue($this->capacity);
		}

		if($this->spokenLanguage) {
			$form['spokenLanguage']->setDefaultValue($this->spokenLanguage->getId());
		}

		return $form;
	}

}
