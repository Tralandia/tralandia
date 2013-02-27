<?php 
namespace FrontModule\Components\SearchBar;

use Nette\Application\UI\Control;
use Service\Seo\ISeoServiceFactory;
use Service\Rental\RentalSearchService;

class SearchBarControl extends \BaseModule\Components\BaseControl {

	const VISIBLE_OPTIONS_COUNT = 15;
	const LOCATION_MAX_COUNT = 100;

	// repositories
	public $rentalTypeRepositoryAccessor;
	public $locationRepositoryAccessor;
	public $languageRepositoryAccessor;
	public $currencyRepositoryAccessor;
	public $primaryLocation;

	// services
	protected $translator;
	protected $seoFactory;
	protected $searchService;

	protected $selected = array();

	public function inject(\Nette\DI\Container $dic) {
		$this->rentalTypeRepositoryAccessor = $dic->rentalTypeRepositoryAccessor;
		$this->locationRepositoryAccessor = $dic->locationRepositoryAccessor;
		$this->languageRepositoryAccessor = $dic->languageRepositoryAccessor;
		$this->currencyRepositoryAccessor = $dic->currencyRepositoryAccessor;
	}

	public function __construct(\Entity\Location\Location $primaryLocation, \Service\Rental\RentalSearchService $searchService, ISeoServiceFactory $seoFactory) {
		$this->primaryLocation = $primaryLocation;
		$this->searchService = $searchService;
		$this->seoFactory = $seoFactory;

		parent::__construct();
	}

	public function render() {

		$this->translator = $this->presenter->getService('translator');
		$this->setSelectedCriteria();

		$template = $this->template;

		// template variables
		$template->criteria = array();
		$template->criteria['rentalType'] 		= $this->getRentalTypeCriteria();
		$template->criteria['location'] 		= $this->getLocationCriteria();

		$template->criteria['flanguage'] 		= $this->getLanguageCriteria();
		$template->criteria['fcapacity'] 		= $this->getCapacityCriteria();
		// $template->criteria['fprice'] 			= $this->getPriceCriteria();

		$template->render();

	}

	protected function setSelectedCriteria() {

		return $this->selected = array(
			'location' => $this->presenter->getParameter('location'),
			'rentalType' => $this->presenter->getParameter('rentalType'),
			'flanguage' => $this->presenter->getParameter('flanguage'),
			'fcapacity' => $this->presenter->getParameter('fcapacity'),
			'fprice' => $this->presenter->getParameter('fprice'),
		);

	}

	protected function getRentalTypeCriteria() {

		$links = $this->getLinksFor(RentalSearchService::CRITERIA_RENTAL_TYPE, $this->rentalTypeRepositoryAccessor);

		return \Nette\ArrayHash::from($links);

	}

	protected function getLocationCriteria() {

		$links = $this->getLinksFor(RentalSearchService::CRITERIA_LOCATION, $this->locationRepositoryAccessor, TRUE);

		return \Nette\ArrayHash::from($links);

	}

	protected function getLanguageCriteria() {

		$links = $this->getLinksFor(RentalSearchService::CRITERIA_SPOKEN_LANGUAGE, $this->languageRepositoryAccessor);

		return \Nette\ArrayHash::from($links);

	}

	protected function getCapacityCriteria() {

		$options = array();
		for ($i=1; $i <= RentalSearchService::CAPACITY_MAX; $i++) {
			$options[$i] = array(
				'id' => $i,
				'name' => $i.' - a',
			);
		}

		$links = $this->getLinksFor(RentalSearchService::CRITERIA_CAPACITY, \Nette\ArrayHash::from($options));

		return \Nette\ArrayHash::from($links);

	}

	protected function getPriceCriteria() {
		
		$currency = $this->primaryLocation->defaultCurrency;
		$searchInterval = $currency->searchInterval;

		$options = array();
		for ($i=1; $i < 10; $i++) { 
			$key = $i*$searchInterval;

			$options[$key] = array(
				'id' => (string) $key,
				'name' => 'do ' . $key,
			);
		}

		$links = $this->getLinksFor(RentalSearchService::CRITERIA_PRICE, \Nette\ArrayHash::from($options));

		return \Nette\ArrayHash::from($links);

	}

	protected function getRentalsCount($params) {
		$this->searchService->resetCriteria();
		foreach ($params as $criteria => $value) {
			$name = 'set'.ucfirst($criteria).'Criterium';
			$this->searchService->{$name}($value);
		}
		return $this->searchService->getRentalsCount();
	}

	protected function getLinksFor($criteriaName, $repositoryAccessor, $ignoreNull = FALSE) {

		$order 		= array();
		$linksTmp 	= array();
		$visible 	= array();
		$selected 	= $this->getSelectedParams();
		$active		= FALSE;

		$options = array();
		if (array_key_exists($criteriaName, $selected)) {
			$active = TRUE;
			if ($repositoryAccessor instanceof \Nette\ArrayHash) {
				$options[] = $repositoryAccessor->{$selected[$criteriaName]};
			} else {
				$options[] = $selected[$criteriaName];
			}
		} else {
			foreach($this->searchService->getCriteriumOptions($criteriaName) as $entityId) {
				if ($repositoryAccessor instanceof \Nette\ArrayHash) {
					$options[] = $repositoryAccessor->{$entityId};
				} else {
					$options[] = $repositoryAccessor->get()->find($entityId);
				}
			}
		}

		$totalRentalCount = 0;
		foreach ($options as $key => $option) {

			$criteriaValue = $option;
			if ($option instanceof \Nette\ArrayHash) $criteriaValue = $option->id;
			$params = array_merge($selected, array($criteriaName => $criteriaValue));
			$count 	= $this->getRentalsCount($params);
			$totalRentalCount += $count;

			if ($ignoreNull && $count==0) continue;

			$linkParams = $params;
			if ($active) unset($linkParams[$criteriaName]);

			if ($option->name instanceof \Entity\Phrase\Phrase) {
				$name = $this->translator->translate($option->name);
			} else {
				$name = $option->name;
			}
			if (strlen($name) == 0) {
				d($criteriaName, $option->id);
			}

			$link 	= $this->presenter->link('//Rental:list', $linkParams);
			$seo 	= $this->seoFactory->create($link, $this->presenter->getLastCreatedRequest());

			$visible[$key] = $count;
			$order[$name] = $key;

			$linksTmp[$key] = array(
				'seo' => $seo,
				'count' => $count,
				'hide' => TRUE,
				'active' => $active,
			);
			if ($option instanceof \Nette\ArrayHash) {
				$linksTmp[$key]['name'] = $option->name;
			} else {
				$linksTmp[$key]['entity'] = $option;
			}
		}

		if ($criteriaName == RentalSearchService::CRITERIA_LOCATION) {
			$visibleTmp = $visible;
			arsort($visibleTmp);
			$visibleTmp = array_slice($visibleTmp, 0, self::LOCATION_MAX_COUNT, TRUE);
			foreach ($linksTmp as $key => $value) {
				if (!isset($visibleTmp[$key])) {
					unset($linksTmp[$key]);
				}
			}
		}
		
		return $this->prepareOrder($linksTmp, $order, $visible);

	}

	protected function getSelectedParams() {

		$selected = array();
		foreach ($this->selected as $criteriaType => $value) {
			if ($value) {
				$selected[$criteriaType] = $value;
			}
		}

		return $selected;

	}

	protected function prepareOrder($linksTmp, $order, $visible) {

		$links = array();

		// sort by counts
		$i=0;
		arsort($visible);
		foreach ($visible as $key => $count) {
			$linksTmp[$key]['hide'] = FALSE;
			if ($i==self::VISIBLE_OPTIONS_COUNT) break;
			$i++;
		}

		// sort by name
		ksort($order);
		//natcasesort
		$links = array();
		foreach ($order as $key) {
			if (isset($linksTmp[$key])) $links[] = $linksTmp[$key];
		}
		return $links;

	}

}

interface ISearchBarControlFactory {
	/**
	 * @param \Entity\Location\Location $location
	 *
	 * @return SearchBarControl
	 */
	public function create(\Entity\Location\Location $location);
}
