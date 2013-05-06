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
	 * @var \Entity\Location\Location|NULL
	 */
	public $location;

	/**
	 * @persistent
	 * @var \Entity\Rental\Type|NULL
	 */
	public $rentalType;

	/**
	 * @persistent
	 * @var \Entity\Rental\Placement|NULL
	 */
	/*placement public $placement; placement*/

	/**
	 * @persistent
	 * @var integer
	 */
	public $priceFrom;

	/**
	 * @persistent
	 * @var integer
	 */
	public $priceTo;

	/**
	 * @persistent
	 * @var integer
	 */
	public $capacity;

	/**
	 * @persistent
	 * @var \Entity\Language|NULL
	 */
	public $spokenLanguage;

	/**
	 * @persistent
	 * @var \Entity\Rental\Amenity|NULL
	 */
	public $board;

	/**
	 * @var \Service\Rental\RentalSearchService
	 */
	protected $search;

	/**
	 * @var \Device
	 */
	protected $device;

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
	 * @var \FrontModule\Forms\ISearchFormFactory
	 */
	protected $searchFormFactory;


	/**
	 * @param \Service\Rental\RentalSearchService $search
	 * @param \Environment\Environment $environment
	 * @param \Doctrine\ORM\EntityManager $em
	 * @param ISearchFormFactory $searchFormFactory
	 * @param \SearchGenerator\OptionGenerator $searchOptionGenerator
	 * @param \Device $device
	 */
	public function __construct(RentalSearchService $search,Environment $environment ,EntityManager $em,
								ISearchFormFactory $searchFormFactory, OptionGenerator $searchOptionGenerator, \Device $device)
	{
		parent::__construct();
		$this->search = $search;
		$this->device = $device;
		$this->environment = $environment;
		$this->em = $em;
		$this->searchFormFactory = $searchFormFactory;
		$this->searchOptionGenerator = $searchOptionGenerator;
	}

	public function render()
	{

		if($this->device->isMobile()){
			$this->template->setFile(APP_DIR.'/FrontModule/components/SearchBar/mobileSearchBarControl.latte');
		}

		$this->directLinks();

		$template = $this->template;
		$presenter = $this->getPresenter();

		$presenter->fillTemplateWithCacheOptions($template);

		$template->isHome = ($presenter->name == 'Front:Home' ? TRUE : FALSE);
		$template->hasLocation = ($this->location ? TRUE : FALSE);

		$jsVariables = [];
		$jsVariables['data-country'] = $this->search->getPrimaryLocation()->getId();

		if($this->location) {
			$jsVariables['data-location-slug'] = $this->location->getSlug();
			$jsVariables['data-location-name'] = $presenter->translate($this->location->getName());
		}

		if($this->rentalType) {
			$pathSegment = $this->em->getRepository(PATH_SEGMENT_ENTITY)
				->findRentalType($this->environment->getLanguage(), $this->rentalType);

			$jsVariables['data-rental-type'] = $pathSegment->getPathSegment();
		}

		/*placement
		if($this->placement) {
			$jsVariables['data-placement'] = $this->placement->getId();
		}
		placement*/

		if($this->priceFrom) {
			$jsVariables['data-price-from'] = $this->priceFrom;
		}

		if($this->priceTo) {
			$jsVariables['data-price-to'] = $this->priceTo;
		}

		if($this->capacity) {
			$jsVariables['data-capacity'] = $this->capacity;
		}

		if($this->spokenLanguage) {
			$jsVariables['data-spoken-language'] = $this->spokenLanguage->getId();
		}

		if($this->board) {
			$jsVariables['data-board'] = $this->board->getId();
		}

		$template->jsVariables = Html::el('variables')->addAttributes($jsVariables);

		$search = $this->getSearch();

		$count = $search->getRentalsCount();
		$template->totalResultsCount = $count;
		$template->totalResultsCountLabel = $presenter->translate('o100002', $count, NULL, ['count' => $count]);
		$template->autocompleteUrl = $presenter->link(':Front:Rental:locationSuggestion', ['page' => NULL]);
		$template->formatInputTooShort = $presenter->translate('o100142');
		$template->bottomLinksCallback = $this->directLinks;
		$template->hhhh = '4';

		$template->render();
	}

	public function directLinks()
	{
		$bottomLinks = [];
		$links = [];
		if(!$this->location && !$this->getPresenter()->isLinkCurrent(':Front:Destination:')) {
			if ($this->getPresenter()->isLinkCurrent(':Front:Home:default')) {
				$count = 300;
			} else {
				$count = 30;
			}
			$links = $this->searchOptionGenerator->generateLocationLinks($count, $this->getSearch());
			$bottomLinks['linkArgument'] = FrontRoute::LOCATION;
			$bottomLinks['title'] = 'o100098';
			$bottomLinks['iconClass'] = 'icon-map-marker';
		}
		if(!count($links) && !$this->rentalType) {
			$links = $this->searchOptionGenerator->generateRentalTypeLinks($this->getSearch());
			$bottomLinks['linkArgument'] = FrontRoute::RENTAL_TYPE;
			$bottomLinks['title'] = 'o20926';
			$bottomLinks['iconClass'] = 'icon-home';
		}
		/*placement
		if(!count($links) && !$this->placement) {
			$links = $this->searchOptionGenerator->generatePlacementLinks($this->getSearch());
			$bottomLinks['linkArgument'] = FrontRoute::PLACEMENT;
			$bottomLinks['title'] = 'o100113';
			$bottomLinks['iconClass'] = 'icon-picture';
		}
		placement*/
		if(!count($links) && !$this->capacity) {
			$links = $this->searchOptionGenerator->generateCapacityLinks($this->getSearch());
			$bottomLinks['linkArgument'] = FrontRoute::CAPACITY;
			$bottomLinks['title'] = 'o20928';
			$bottomLinks['iconClass'] = 'icon-user';
		}
		if(!count($links) && !$this->board) {
			$links = $this->searchOptionGenerator->generateBoardLinks($this->getSearch());
			$bottomLinks['linkArgument'] = FrontRoute::BOARD;
			$bottomLinks['title'] = 'o100080';
			$bottomLinks['iconClass'] = 'icon-food';
		}
		if(!count($links) && !$this->spokenLanguage) {
			$links = $this->searchOptionGenerator->generateSpokenLanguageLinks($this->getSearch());
			$bottomLinks['linkArgument'] = FrontRoute::SPOKEN_LANGUAGE;
			$bottomLinks['title'] = 'o20930';
			$bottomLinks['iconClass'] = 'icon-globe';
		}
		if(!count($links) && !$this->priceFrom) {
			$links = $this->searchOptionGenerator->generatePriceLinks($this->environment->getCurrency(), $this->getSearch());
			$bottomLinks['linkArgument'] = FrontRoute::PRICE_FROM;
			$bottomLinks['title'] = 'o100093';
			$bottomLinks['iconClass'] = 'icon-money22';
		}
		if(!count($links) && !$this->priceTo) {
			$links = $this->searchOptionGenerator->generatePriceLinks($this->environment->getCurrency(), $this->getSearch(), $this->priceFrom);
			$bottomLinks['linkArgument'] = FrontRoute::PRICE_TO;
			$bottomLinks['title'] = 'o100094';
			$bottomLinks['iconClass'] = 'icon-money22';
		}

		$bottomLinks['links'] = $links;

		return ArrayHash::from($bottomLinks);
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

		$label = $this->getPresenter()->translate('o100002', $count, NULL, ['count' => $count]);

		$this->presenter->sendJson(['count' => $count, 'label' => $label]);
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

		$search->setPriceCriterion($this->priceFrom, $this->priceTo);

		if ($this->capacity) {
			$search->setCapacityCriterion($this->capacity);
		}

		if ($this->board) {
			$search->setBoardCriterion($this->board);
		}

		/*placement
		if ($this->placement) {
			$search->setPlacementCriterion($this->placement);
		}
		placement*/

		if ($this->spokenLanguage) {
			$search->setSpokenLanguageCriterion($this->spokenLanguage);
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
		$form = $this->searchFormFactory->create($this->getSearchCriteria(), $this->getSearch(), $this->presenter);
		//$form->buildForm();

		$form->onSuccess[] = function ($form) {
			$values = $form->getValues();
			$form->presenter->redirect('RentalList', (array) $values);
		};

		return $form;
	}

}
