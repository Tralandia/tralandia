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
		$template->criteria['spokenLanguage'] 	= $this->getSpokenLanguageCriteria();

		$template->criteria['capacity'] 		= $this->getCapacityCriteria();
		// $template->criteria['price'] 		= $this->getPriceCriteria();

		$template->render();

	}

	protected function setSelectedCriteria() {

		return $this->selected = array(
			'location' => $this->presenter->getParameter('location'),
			'rentalType' => $this->presenter->getParameter('rentalType'),
			'rentalTag' => $this->presenter->getParameter('rentalTag'),
			'spokenLanguage' => $this->presenter->getParameter('spokenLanguage'),
			'capacity' => $this->presenter->getParameter('capacity'),
			'price' => $this->presenter->getParameter('price'),
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

	protected function getSpokenLanguageCriteria() {

		$links = $this->getLinksFor(RentalSearchService::CRITERIA_SPOKEN_LANGUAGE, $this->languageRepositoryAccessor);

		return \Nette\ArrayHash::from($links);

	}

	protected function getCapacityCriteria() {

		$order 		= array();
		$linksTmp 	= array();
		$visible 	= array();
		$selected 	= $this->getSelectedParams();
		$active		= FALSE;

		$entities = array();
		if (array_key_exists('capacity', $selected)) {
			
			$active = TRUE;
			$entities[] = $selected['capacity'];

		} else {
			
			for ($i=0; $i < 50; $i++) {

				$params = array_merge($selected, array('capacity' => $i));

				$name = $i;

				$count = $this->getRentalsCount($params);
				$link = $this->presenter->link('//Rental:list', $params);
				$seo = $this->seoFactory->create($link, $this->presenter->getLastCreatedRequest());

				$visible[$i] = $count;
				$order[$name] = $i;

				$linksTmp[$i] = array(
					'seo' => $seo,
					'name' => $i,
					'count' => $count,
					'hide' => TRUE,
					'active' => $active,
				);
			}
		}

		$links = $this->prepareOrder($linksTmp, $order, $visible);

		return \Nette\ArrayHash::from($links);

	}

/*
	protected function getPriceCriteria() {

		$links = array();
		$visible = array();
		$selected = $this->getSelectedParams();

		$currency = $this->primaryLocation->defaultCurrency;
		$searchInterval = $currency->searchInterval;

		for ($i=0; $i < 10; $i++) { 
			$from = $i*$searchInterval+1;
			$to = $searchInterval*($i+1);

			$params = array_merge($selected, array('price' => $i));

			$link = $this->presenter->link('//Rental:list', $params);
			$seo = $this->seoFactory->create($link, $this->presenter->getLastCreatedRequest());

			$count = $this->getRentalsCount($params);

			$visible[$i] = $count;

			$links[$i] = array(
				'seo' => $seo,
				'name' => ($from . ' do ' . $to),
				'count' => $count,
				'hide' => TRUE,
			);
		}

		$i=0;
		arsort($visible);
		foreach ($visible as $key => $value) {
			$links[$key]['hide'] = FALSE;
			if ($i==self::VISIBLE_OPTIONS_COUNT) break;
			$i++;
		}

		return \Nette\ArrayHash::from($links);

	}
*/

	protected function getRentalsCount($params) {
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

		$entities = array();
		if (array_key_exists($criteriaName, $selected)) {
			$active = TRUE;
			$entities[] = $selected[$criteriaName];
		} else {
			foreach($this->searchService->getCriteriumOptions($criteriaName) as $entityId) {
				$entities[] = $repositoryAccessor->get()->find($entityId);
			}
		}

		foreach ($entities as $key => $entity) {
			$params = array_merge($selected, array($criteriaName => $entity));

			$count 	= $this->getRentalsCount($params);
			if ($ignoreNull && $count==0) continue;

			$linkParams = $params;
			if ($active) unset($linkParams[$criteriaName]);

			$name 	= $this->translator->translate($entity->name);
			$link 	= $this->presenter->link('//Rental:list', $linkParams);
			$seo 	= $this->seoFactory->create($link, $this->presenter->getLastCreatedRequest());

			$visible[$key] = $count;
			$order[$name] = $key;
			$linksTmp[$key] = array(
				'seo' => $seo,
				'count' => $count,
				'hide' => TRUE,
				'active' => $active,
				'entity' => $entity,
			);
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
