<?php 
namespace FrontModule\Components\SearchBar;

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
	 * @param ISearchFormFactory $searchFormFactory
	 * @param \SearchGenerator\OptionGenerator $searchOptionGenerator
	 */
	public function __construct(RentalSearchService $search, ISearchFormFactory $searchFormFactory, OptionGenerator $searchOptionGenerator)
	{
		parent::__construct();
		$this->search = $search;
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

		$template->render();
	}

	public function handleGetSearchCount()
	{
		$search = $this->getSearch();

		$count = $search->getRentalsCount();

		$this->presenter->sendJson(['count' => $count]);
	}

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
			$form->presenter->redirect('this', (array) $values);
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
