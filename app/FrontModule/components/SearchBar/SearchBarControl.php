<?php 
namespace FrontModule\Components\SearchBar;

use Nette\Application\UI\Control;
use Service\Seo\ISeoServiceFactory;
use Service\Rental\RentalSearchService;

class SearchBarControl extends \BaseModule\Components\BaseControl {

	const VISIBLE_OPTIONS_COUNT = 15;

	// repositories
	public $rentalTypeRepositoryAccessor;
	public $rentalTagRepositoryAccessor;
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
		$this->rentalTagRepositoryAccessor = $dic->rentalTagRepositoryAccessor;
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
		$template->setFile(dirname(__FILE__) . '/sidebar.latte');
		$template->setTranslator($this->translator);

		// template variables
		$template->criteria = array();
		$template->criteria['rentalType'] 		= $this->getRentalTypeCriteria();
		$template->criteria['location'] 		= $this->getLocationCriteria();
		$template->criteria['rentalTag'] 		= $this->getRentalTagCriteria();

		$template->criteria['flanguage'] 		= $this->getLanguageCriteria();
		$template->criteria['fcapacity'] 		= $this->getCapacityCriteria();
		$template->criteria['fprice'] 			= $this->getPriceCriteria();

		$template->render();

	}

	protected function setSelectedCriteria() {

		return $this->selected = array(
			'location' => $this->presenter->getParameter('location'),
			'rentalType' => $this->presenter->getParameter('rentalType'),
			'rentalTag' => $this->presenter->getParameter('rentalTag'),
			'spokenLanguage' => $this->presenter->getParameter('spokenLanguage'),
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

	protected function getRentalTagCriteria() {

		$links = $this->getLinksFor(RentalSearchService::CRITERIA_TAG, $this->rentalTagRepositoryAccessor);

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
				'name' => $i,
			);
		}

		$links = $this->getLinksFor(RentalSearchService::CRITERIA_CAPACITY, \Nette\ArrayHash::from($options));

		return \Nette\ArrayHash::from($links);

	}

	protected function getPriceCriteria() {
		
		$currency = $this->primaryLocation->defaultCurrency;
		$searchInterval = $currency->searchInterval;

		$options = array();
		for ($i=0; $i < 10; $i++) { 

			$to = $searchInterval*($i+1);

			$options[$i*$searchInterval] = array(
				'id' => $i,
				'name' => ' do ' . $to,
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

	protected function getLinksFor($criteriaName, $repositoryAccessor, $ignoreNull=FALSE) {

		$order 		= array();
		$linksTmp 	= array();
		$visible 	= array();
		$selected 	= $this->getSelectedParams();
		$active		= FALSE;

		$options = array();
		if (array_key_exists($criteriaName, $selected)) {
			$active = TRUE;
			$options[] = $selected[$criteriaName];
		} else {
			foreach($this->searchService->getCriteriumOptions($criteriaName) as $entityId) {
				if ($repositoryAccessor instanceof \Nette\ArrayHash) {
					$options[] = $repositoryAccessor->{$entityId};
				} else {
					$options[] = $repositoryAccessor->get()->find($entityId);
				}
			}
		}

		foreach ($options as $key => $option) {

			$params = array_merge($selected, array($criteriaName => $option));

			$count 	= $this->getRentalsCount($params);
			if ($ignoreNull && $count==0) continue;

			$linkParams = $params;
			if ($active) unset($linkParams[$criteriaName]);

			if ($option->name instanceof \Entity\Phrase\Phrase) {
				$name = $this->translator->translate($option->name);
			} else {
				$name = $option->name;
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
		$links = array();
		foreach ($order as $key) {
			$links[] = $linksTmp[$key];
		}

		return $links;

	}

}
