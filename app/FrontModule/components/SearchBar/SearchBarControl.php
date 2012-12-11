<?php 
namespace FrontModule\Components\SearchBar;

use Nette\Application\UI\Control;
use Service\Seo\ISeoServiceFactory;

class SearchBarControl extends \BaseModule\Components\BaseControl {

	public $rentalTypeRepositoryAccessor;
	public $rentalTagRepositoryAccessor;
	public $locationRepositoryAccessor;
	public $languageRepositoryAccessor;
	public $currencyRepositoryAccessor;
	public $primaryLocation;

	protected $seoFactory;
	protected $searchServiceFactory;

	protected $selected = array();

	public function injectSeo(ISeoServiceFactory $seoFactory) {

		$this->seoFactory = $seoFactory;
	}

	public function injectRentalSearchService(\Service\Rental\IRentalSearchServiceFactory $searchServiceFactory) {

		$this->searchServiceFactory = $searchServiceFactory;
	}

	public function inject(\Nette\DI\Container $dic) {
		$this->rentalTypeRepositoryAccessor = $dic->rentalTypeRepositoryAccessor;
		$this->rentalTagRepositoryAccessor = $dic->rentalTagRepositoryAccessor;
		$this->locationRepositoryAccessor = $dic->locationRepositoryAccessor;
		$this->languageRepositoryAccessor = $dic->languageRepositoryAccessor;
		$this->currencyRepositoryAccessor = $dic->currencyRepositoryAccessor;
	}

	public function __construct(\Entity\Location\Location $primaryLocation) {
		$this->primaryLocation = $primaryLocation;

		parent::__construct();
	}

	public function render() {

		$this->setSelectedCriteria();

		$template = $this->template;
		$template->setFile(dirname(__FILE__) . '/sidebar.latte');
		$template->setTranslator($this->presenter->getService('translator'));

		// template variables
		$template->criteriaSpokenLanguage 	= $this->getSpokenLanguageCriteria();
		$template->criteriaRentalType 		= $this->getRentalTypeCriteria();
		$template->criteriaRentalTag 		= $this->getRentalTagCriteria();
		$template->criteriaLocation 		= $this->getLocationCriteria();
		$template->criteriaCapacity 		= $this->getCapacityCriteria();
		$template->criteriaPrice 			= $this->getPriceCriteria();

		$template->render();
	}

	protected function setSelectedCriteria() {

		return $this->selected = array(
			'rentalTag' => $this->presenter->getParameter('rentalTag'),
			'location' => $this->presenter->getParameter('location'),
			'rentalType' => $this->presenter->getParameter('rentalType'),
			'location' => $this->presenter->getParameter('location'),
			'spokenLanguage' => $this->presenter->getParameter('spokenLanguage'),
			'capacity' => $this->presenter->getParameter('capacity'),
		);

	}

	protected function getRentalTagCriteria() {

		$links = array();
		$showLinks = array();
		$selected = $this->getSelectedParams();

		$rentalTags = $this->rentalTagRepositoryAccessor->get()->findAll();
		foreach ($rentalTags as $key => $rentalTag) {
			$params = array_merge($selected, array('rentalTag' => $rentalTag));

			$link = $this->presenter->link('//Rental:list', $params);
			$seo = $this->seoFactory->create($link, $this->presenter->getLastCreatedRequest());

			$count = $this->getRentalsCount($params);

			$showLinks[$key] = $count;

			$links[$key] = array(
				'seo' => $seo,
				'count' => $count,
				'hide' => TRUE,
			);
		}

		$i=0;
		arsort($showLinks);
		foreach ($showLinks as $key => $value) {
			$links[$key]['hide'] = FALSE;
			if ($i==10) break;
			$i++;
		}

		return \Nette\ArrayHash::from($links);

	}

	protected function getRentalTypeCriteria() {

		$links = array();
		$selected = $this->getSelectedParams();

		$rentalTypes = $this->rentalTypeRepositoryAccessor->get()->findAll();
		foreach ($rentalTypes as $key => $rentalType) {
			$params = array_merge($selected, array('rentalType' => $rentalType));

			$link = $this->presenter->link('//Rental:list', $params);
			$seo = $this->seoFactory->create($link, $this->presenter->getLastCreatedRequest());

			$count = $this->getRentalsCount($params);

			$showLinks[$key] = $count;

			$links[$key] = array(
				'seo' => $seo,
				'count' => $count,
				'hide' => TRUE,
			);
		}

		$i=0;
		arsort($showLinks);
		foreach ($showLinks as $key => $value) {
			$links[$key]['hide'] = FALSE;
			if ($i==10) break;
			$i++;
		}

		return \Nette\ArrayHash::from($links);

	}

	protected function getLocationCriteria() {

		$links = array();
		$selected = $this->getSelectedParams();

		$locations = $this->locationRepositoryAccessor->get()->findByParent($this->primaryLocation);
		foreach ($locations as $key => $location) {
			$params = array_merge($selected, array('location' => $location));

			$link = $this->presenter->link('//Rental:list', $params);
			$seo = $this->seoFactory->create($link, $this->presenter->getLastCreatedRequest());

			$count = $this->getRentalsCount($params);

			$showLinks[$key] = $count;

			$links[$key] = array(
				'seo' => $seo,
				'count' => $count,
				'hide' => TRUE,
			);
		}

		$i=0;
		arsort($showLinks);
		foreach ($showLinks as $key => $count) {
			$links[$key]['hide'] = FALSE;
			if ($i==10) break;
			$i++;
		}

		return \Nette\ArrayHash::from($links);

	}

	protected function getSpokenLanguageCriteria() {

		$links = array();
		$selected = $this->getSelectedParams();

		$languages = $this->languageRepositoryAccessor->get()->findAll();
		foreach ($languages as $key => $language) {
			$params = array_merge($selected, array('spokenLanguage' => $language));

			$link = $this->presenter->link('//Rental:list', $params);
			$seo = $this->seoFactory->create($link, $this->presenter->getLastCreatedRequest());

			$count = $this->getRentalsCount($params);

			$showLinks[$key] = $count;

			$links[$key] = array(
				'seo' => $seo,
				'count' => $count,
				'hide' => TRUE,
			);
		}

		$i=0;
		arsort($showLinks);
		foreach ($showLinks as $key => $value) {
			$links[$key]['hide'] = FALSE;
			if ($i==10) break;
			$i++;
		}

		return \Nette\ArrayHash::from($links);

	}

	protected function getCapacityCriteria() {

		$links = array();
		$selected = $this->getSelectedParams();

		for ($i=0; $i < 50; $i++) {

			$params = array_merge($selected, array('capacity' => $i));
			$link = $this->presenter->link('//Rental:list', $params);

			$seo = $this->seoFactory->create($link, $this->presenter->getLastCreatedRequest());
			$count = $this->getRentalsCount($params);

			$showLinks[$i] = $count;

			$links[$i] = array(
				'seo' => $seo,
				'count' => $count,
				'hide' => TRUE,
			);
		}

		$i=0;
		arsort($showLinks);
		foreach ($showLinks as $key => $value) {
			$links[$key]['hide'] = FALSE;
			if ($i==10) break;
			$i++;
		}

		return \Nette\ArrayHash::from($links);

	}

	protected function getPriceCriteria() {

		$links = array();
		$selected = $this->getSelectedParams();

		$currency = $this->primaryLocation->defaultCurrency;
		$searchInterval = $currency->searchInterval;

		for ($i=0; $i <= 10; $i++) { 
			$from = $i*$searchInterval+1;
			$to = $searchInterval*($i+1);

			$params = array_merge($selected, array('price' => $i));

			$link = $this->presenter->link('//Rental:list', $params);
			$seo = $this->seoFactory->create($link, $this->presenter->getLastCreatedRequest());

			$count = $this->getRentalsCount($params);

			$showLinks[$i] = $count;

			$links[$i] = array(
				'seo' => $seo,
				'name' => ($from . ' do ' . $to),
				'count' => $count,
				'hide' => TRUE,
			);
		}

		$i=0;
		arsort($showLinks);
		foreach ($showLinks as $key => $value) {
			$links[$key]['hide'] = FALSE;
			if ($i==10) break;
			$i++;
		}

		return \Nette\ArrayHash::from($links);

	}

	protected function getRentalsCount($params) {
		$searchService = $this->searchServiceFactory->create($this->primaryLocation);
		foreach ($params as $criteria => $value) {
			$name = 'add'.ucfirst($criteria).'Criteria';
			$searchService->{$name}($value);
		}
		return $searchService->getRentalsCount();
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

}
