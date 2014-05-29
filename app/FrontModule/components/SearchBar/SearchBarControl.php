<?php
namespace FrontModule\Components\SearchBar;

use Doctrine\ORM\EntityManager;
use Entity\BaseEntity;
use Entity\FavoriteList;
use Entity\Location\Location;
use Entity\Rental\Type;
use Environment\Environment;
use FrontModule\Components\SearchHistory\SearchHistoryControl;
use FrontModule\Components\VisitedRentals\VisitedRentalsControl;
use FrontModule\Forms\ISearchFormFactory;
use Nette\ArrayHash;
use Nette\Utils\Html;
use Nette\Utils\Strings;
use Routers\FrontRoute;
use SearchGenerator\OptionGenerator;
use Service\Rental\RentalSearchService;
use Tralandia\GpsSearchLog\GpsSearchLogManager;
use Tralandia\Routing\PathSegments;

class SearchBarControl extends \BaseModule\Components\BaseControl {

	/**
	 * @persistent
	 * @var \Entity\Location\Location|NULL
	 */
	public $location;

	/**
	 * @var string|NULL
	 */
	public $address;

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
	 * @var float
	 */
	public $latitude;

	/**
	 * @persistent
	 * @var float
	 */
	public $longitude;

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
	 * @var \Tralandia\Routing\PathSegments
	 */
	private $pathSegments;

	/**
	 * @var SearchHistoryControl
	 */
	private $searchHistory;

	/**
	 * @var VisitedRentalsControl
	 */
	private $visitedRentals;

	/**
	 * @var \Tralandia\GpsSearchLog\GpsSearchLogManager
	 */
	private $gpsSearchLogManager;


	/**
	 * @param \Service\Rental\RentalSearchService $search
	 * @param \Environment\Environment $environment
	 * @param \Doctrine\ORM\EntityManager $em
	 * @param ISearchFormFactory $searchFormFactory
	 * @param \Tralandia\Routing\PathSegments $pathSegments
	 * @param \SearchGenerator\OptionGenerator $searchOptionGenerator
	 * @param \Device $device
	 * @param SearchHistoryControl $searchHistory
	 * @param VisitedRentalsControl $visitedRentals
	 */
	public function __construct(RentalSearchService $search,Environment $environment ,EntityManager $em,
								ISearchFormFactory $searchFormFactory, PathSegments $pathSegments,
								OptionGenerator $searchOptionGenerator, \Device $device, GpsSearchLogManager $gpsSearchLogManager,
								SearchHistoryControl $searchHistory, VisitedRentalsControl $visitedRentals)
	{
		parent::__construct();
		$this->search = $search;
		$this->device = $device;
		$this->environment = $environment;
		$this->em = $em;
		$this->searchFormFactory = $searchFormFactory;
		$this->searchOptionGenerator = $searchOptionGenerator;
		$this->pathSegments = $pathSegments;
		$this->searchHistory = $searchHistory;
		$this->visitedRentals = $visitedRentals;
		$this->gpsSearchLogManager = $gpsSearchLogManager;
	}

	public function render()
	{

		if($this->device->isMobile()){
			$this->template->setFile(APP_DIR.'/FrontModule/components/SearchBar/mobileSearchBarControl.latte');
		}

		$template = $this->template;
		$presenter = $this->getPresenter();

		$presenter->fillTemplateWithCacheOptions($template);

		$template->isHome = ($presenter->name == 'Front:Home' ? TRUE : FALSE);
		$template->hasLocation = ($this->location ? TRUE : FALSE);

		$jsVariables = [];
		$jsVariables['data-country'] = $this->search->getPrimaryLocation()->getId();

		if($this->location) {
//			$jsVariables['data-location-slug'] = $this->location->getSlug();
//			$jsVariables['data-location-name'] = $presenter->translate($this->location->getName());
			$jsVariables['data-address'] = $presenter->translate($this->location->getName());
		}
		
		if($this->address) {
			$jsVariables['data-address'] = $this->address;
		}

		if($this->rentalType) {
			$pathSegment = $this->pathSegments->findRentalType($this->environment->getLanguage(), $this->rentalType);

			$jsVariables['data-rental-type'] = $pathSegment->getPathSegment();
		}

		/*placement
		if($this->placement) {
			$jsVariables['data-placement'] = $this->placement->getId();
		}
		placement*/

		if($this->priceFrom !== NULL) {
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
		$template->noResultsText = $presenter->translate('151891');
		$template->bottomLinksCallback = $this->directLinks;
		$template->isHomepage = $this->isHomepage;
		$template->environmentLocation = $this->environment->getPrimaryLocation();

		$template->render();
	}


	public function renderBreadcrumb()
	{
		$this->template->setFile(APP_DIR.'/FrontModule/components/SearchBar/breadcrumb.latte');
		$template = $this->template;

		if($this->presenter->getName() == 'Front:Rental') {
			/** @var $rental \Entity\Rental\Rental */
			$rental = $this->presenter->getParameter('rental');
			$filter = [
				FrontRoute::$pathParametersMapper[FrontRoute::RENTAL_TYPE] => $rental->getType(),
				FrontRoute::$pathParametersMapper[FrontRoute::LOCATION] => $rental->getAddress()->getLocality(),
				FrontRoute::PRIMARY_LOCATION => $rental->getPrimaryLocation(),
			];
		} else if($favoriteList = $this->presenter->getParameter('favoriteList')) {
			// 1219
			$filter = [
				FrontRoute::FAVORITE_LIST => $favoriteList,
				FrontRoute::PRIMARY_LOCATION => $this->environment->getPrimaryLocation(),
			];
		} else {

			$filter = [
				FrontRoute::$pathParametersMapper[FrontRoute::SPOKEN_LANGUAGE] => $this->spokenLanguage,
				FrontRoute::$pathParametersMapper[FrontRoute::BOARD] => $this->board,
				FrontRoute::$pathParametersMapper[FrontRoute::CAPACITY] => $this->capacity,
				FrontRoute::$pathParametersMapper[FrontRoute::PRICE_FROM] => $this->priceFrom,
				FrontRoute::$pathParametersMapper[FrontRoute::PRICE_TO] => $this->priceTo,
				FrontRoute::$pathParametersMapper[FrontRoute::RENTAL_TYPE] => $this->rentalType,
				FrontRoute::$pathParametersMapper[FrontRoute::LOCATION] => $this->location,
				FrontRoute::PRIMARY_LOCATION => $this->environment->getPrimaryLocation(),
			];
			$filter = array_filter($filter);
		}
		$searchLink = $this->presenter->link(':Front:RentalList:', $filter);


		$breadcrumbLinks = [];
		foreach($filter as $key => $value) {

			if(($key == FrontRoute::$pathParametersMapper[FrontRoute::PRICE_FROM] || $key == FrontRoute::$pathParametersMapper[FrontRoute::PRICE_TO])
				&& ($this->priceFrom || $this->priceTo))
			{
				$currency = $this->environment->getPrimaryLocation()->getDefaultCurrency()->getIso();

				if($this->priceFrom) {
					$from = $this->getPresenter()->translate('o100093') . ' ' . $this->priceFrom . ' ';
				} else {
					$from = NULL;
				}

				if($this->priceTo) {
					$to = $this->getPresenter()->translate('o100094') . ' ' . $this->priceTo . ' ';
				} else {
					$to = NULL;
				}

				$value = $from . $to . strtoupper($currency);
				$key = 'price';
			}

			if($value) {
				$link = [];
				$link['href'] = $this->presenter->link(':Front:RentalList:', $filter);
				if($key == FrontRoute::$pathParametersMapper[FrontRoute::CAPACITY]) {
					$link['text'] = $value . ' ' . $this->presenter->translate('o490', $value);
				} else if ($value instanceof FavoriteList) {
					$link['text'] = $this->getPresenter()->translate(1219);
				} else if ($value instanceof Location) {
					$name = $this->getPresenter()->getLocationName($value);
					$link['text'] = $name['name'];
					if(!$value->isPrimary() && isset($name['nameSource'])) {
						$link['description'] = $name['nameSource'];
					}
				} else if ($value instanceof Type) {
					$link['text'] = Strings::firstUpper($this->getPresenter()->translate($value->getName(), 2));
				} else if($value instanceof BaseEntity) {
					$link['text'] = $this->getPresenter()->translate($value->getName());
				} else {
					$link['text'] = $value;
				}

				$breadcrumbLinks[$key] = $link;
			}

			if($key == 'price') {
				$filter[FrontRoute::$pathParametersMapper[FrontRoute::PRICE_FROM]] = NULL;
				$filter[FrontRoute::$pathParametersMapper[FrontRoute::PRICE_TO]] = NULL;
			} else {
				$filter[$key] = NULL;
			}
		}

		$breadcrumbLinks = array_reverse($breadcrumbLinks, TRUE);

		$breadcrumbLinks = ArrayHash::from($breadcrumbLinks);

		$template->searchLink = $searchLink;
		$template->breadcrumbLinks = $breadcrumbLinks;
		$template->isList = $this->getPresenter()->isLinkCurrent(':Front:RentalList:*');


		$template->render();
	}


	public function isHomepage()
	{
		return $this->presenter->getName() == 'Front:Home';
	}


	public function directLinks()
	{
		$bottomLinks = [];
		$links = [];
		if(!$this->location && !$this->latitude && !$this->getPresenter()->isLinkCurrent(':Front:Destination:')) {
			$count = 30;
			$links = $this->searchOptionGenerator->generateLocationLinks($count, $this->getSearch());
			$bottomLinks['linkArgument'] = FrontRoute::LOCATION;
			$bottomLinks['title'] = 'o100098';
			$bottomLinks['iconClass'] = 'icon-map-marker';
		}

		if(!count($links) && !$this->rentalType) {
			$links = $this->searchOptionGenerator->generateRentalTypeLinks($this->getSearch());
			$bottomLinks['linkArgument'] = FrontRoute::RENTAL_TYPE;
			$bottomLinks['title'] = '151884';
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

		if ($this->priceFrom || $this->priceTo) {
			$search->setPriceCriterion($this->priceFrom, $this->priceTo);
		}

		if ($this->capacity) {
			$search->setCapacityCriterion($this->capacity);
		}

		if ($this->board) {
			$search->setBoardCriterion($this->board);
		}

		if ($this->latitude && $this->longitude) {
			$search->setGpsCriterion($this->latitude, $this->longitude);
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

		$form->onSuccess[] = function ($form) {
			$values = $form->getValues();
			$form->presenter->redirect('RentalList', (array) $values);
		};

		return $form;
	}

	protected function createComponentSearchHistory()
	{
		$component = $this->searchHistory;

		return $component;
	}

	protected function createComponentVisitedRentals()
	{
		$component = $this->visitedRentals;

		return $component;
	}


	public function attached($presenter)
	{
		parent::attached($presenter);

		if($this->latitude && $this->longitude) {
			$gps = $this->gpsSearchLogManager->findOneByGps($this->latitude, $this->longitude);
			if($gps) {
				$this->address = $gps->text;
			}
		}
	}

}
