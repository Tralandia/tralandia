<?php
namespace FrontModule\Components\SearchBar;

use Doctrine\ORM\EntityManager;
use Environment\Environment;
use FrontModule\Forms\ISearchFormFactory;
use Nette\ArrayHash;
use Nette\Utils\Html;
use Routers\FrontRoute;
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
	 * @var \Environment\Environment
	 */
	protected $environment;

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
	 * @param \Environment\Environment $environment
	 * @param \Doctrine\ORM\EntityManager $em
	 * @param ISearchFormFactory $searchFormFactory
	 * @param \SearchGenerator\OptionGenerator $searchOptionGenerator
	 */
	public function __construct(RentalSearchService $search,Environment $environment ,EntityManager $em,
								ISearchFormFactory $searchFormFactory, OptionGenerator $searchOptionGenerator)
	{
		parent::__construct();
		$this->search = $search;
		$this->environment = $environment;
		$this->em = $em;
		$this->searchFormFactory = $searchFormFactory;
		$this->searchOptionGenerator = $searchOptionGenerator;
	}

	public function render()
	{
		$this->directLinks();

		$template = $this->template;

		$jsVariables = [];
		$jsVariables['data-country'] = $this->search->getPrimaryLocation()->getId();

		if($this->location) {
			$jsVariables['data-location'] = $this->location->getSlug();
		}

		if($this->rentalType) {
			$pathSegment = $this->em->getRepository(PATH_SEGMENT_ENTITY)
				->findRentalType($this->environment->getLanguage(), $this->rentalType);

			$jsVariables['data-rentalType'] = $pathSegment->getPathSegment();
		}

		if($this->priceFrom) {
			$jsVariables['data-priceFrom'] = $this->priceFrom;
		}

		if($this->priceTo) {
			$jsVariables['data-priceTo'] = $this->priceTo;
		}

		if($this->capacity) {
			$jsVariables['data-capacity'] = $this->capacity;
		}

		if($this->spokenLanguage) {
			$jsVariables['data-spokenLanguage'] = $this->spokenLanguage->getId();
		}

		$template->jsVariables = Html::el('variables')->addAttributes($jsVariables);

		$search = $this->getSearch();

		$template->totalResultsCount = $search->getRentalsCount();

		$template->render();
	}

	public function directLinks()
	{
		$template = $this->template;

		$bottomLinks = [];
		if(!$this->location) {
			if($this->getPresenter()->isLinkCurrent(':Front:Home:default')) {
				$count = 300;
			} else {
				$count = 30;
			}
			$links = $this->searchOptionGenerator->generateLocationLinks($count);
			$bottomLinks['linkArgument'] = FrontRoute::LOCATION;
			$bottomLinks['title'] = 'o100098';
			$bottomLinks['iconClass'] = 'icon-map-marker';
		} else if(!$this->rentalType) {
			$links = $this->searchOptionGenerator->generateRentalTypeLinks($this->location);
			$bottomLinks['linkArgument'] = FrontRoute::RENTAL_TYPE;
			$bottomLinks['title'] = 'o20926';
			$bottomLinks['iconClass'] = 'icon-home';
		} else if(!$this->priceFrom) {
			$links = $this->searchOptionGenerator->generatePriceLinks($this->environment->getCurrency(), $this->getSearch());
			$bottomLinks['linkArgument'] = FrontRoute::PRICE_FROM;
			$bottomLinks['title'] = 'o100098';
			$bottomLinks['iconClass'] = 'icon-map-marker';
		} else if(!$this->priceTo) {
			$links = $this->searchOptionGenerator->generatePriceLinks($this->environment->getCurrency(), $this->getSearch());
			$bottomLinks['linkArgument'] = FrontRoute::PRICE_TO;
			$bottomLinks['title'] = 'o100098';
			$bottomLinks['iconClass'] = 'icon-map-marker';
		} else if(!$this->capacity) {
			$links = $this->searchOptionGenerator->generateCapacityLinks($this->getSearch());
			$bottomLinks['linkArgument'] = FrontRoute::CAPACITY;
			$bottomLinks['title'] = 'o100098';
			$bottomLinks['iconClass'] = 'icon-map-marker';
		} else if(!$this->spokenLanguage) {
			$links = $this->searchOptionGenerator->generateSpokenLanguageLinks($this->getSearch());
			$bottomLinks['linkArgument'] = FrontRoute::SPOKEN_LANGUAGE;
			$bottomLinks['title'] = 'o100098';
			$bottomLinks['iconClass'] = 'icon-map-marker';
		} else {
			return NULL;
		}

		$bottomLinks['links'] = $links;

		$template->bottomLinks = ArrayHash::from($bottomLinks);
	}

	public function handleGetSearchCount()
	{
//		$em = $this->em;
//		$presenter = $this->getPresenter();
//
//		$location = $presenter->getParameter('location');
//		if($location) {
//			$this->location = $em->getRepository(LOCATION_ENTITY)->find($location);
//		}
//
//		$rentalType = $presenter->getParameter('rentalType');
//		if($rentalType) {
//			$this->rentalType = $em->getRepository(RENTAL_TYPE_ENTITY)->find($rentalType);
//		}
//
//		$this->priceFrom = $presenter->getParameter('priceFrom', NULL);
//
//		$this->priceTo = $presenter->getParameter('priceTo', NULL);
//
//		$this->capacity = $presenter->getParameter('capacity', NULL);
//
//		$spokenLanguage = $presenter->getParameter('spokenLanguage');
//		if($spokenLanguage) {
//			$this->spokenLanguage = $em->getRepository(LANGUAGE_ENTITY)->find($spokenLanguage);
//		}

		$search = $this->getSearch();

		$count = $search->getRentalsCount();

		$count = $count . ' ' . $this->getPresenter()->translate('o100002', $count);

		$this->presenter->sendJson(['count' => $count]);
	}

	/**
	 * @return \Service\Rental\RentalSearchService
	 */
	public function getSearch()
	{
		$search = $this->search;

		if ($this->location) {
			$search->setLocationCriterion($this->location);
		}

		if ($this->rentalType) {
			$search->setRentalTypeCriterion($this->rentalType);
		}

		return $search;
	}

	protected function hasSearchCriteria()
	{
		return (bool) count($this->getSearchCriteria());
	}

	protected function hasOnlyLocationCriterion()
	{
		$searchCriteria = $this->getSearchCriteria();
		return count($searchCriteria) == 1 && array_key_exists(FrontRoute::LOCATION ,$searchCriteria);
	}

	public function getSearchCriteria()
	{
		$criteria = [];
		foreach($this->getPersistentParams() as $parameterName) {
			$criteria[$parameterName] = $this->{$parameterName};
		}
		return array_filter($criteria);
	}

	/**
	 * @return \FrontModule\Forms\SearchForm
	 */
	protected function createComponentSearchForm()
	{
		$form = $this->searchFormFactory->create($this->presenter);
		//$form->buildForm();

		$form->onSuccess[] = function ($form) {
			$values = $form->getValues();
			$form->presenter->redirect('RentalList', (array) $values);
		};

		return $form;
	}

}
