<?php 
namespace FrontModule\Components\SearchBar;

use Nette\Application\UI\Control;
use Service\Seo\ISeoServiceFactory;

class SearchBarControl extends \BaseModule\Components\BaseControl {

	public $rentalTypeRepositoryAccessor;
	public $rentalTagRepositoryAccessor;
	public $primaryLocation;

	protected $seoFactory;

	protected $selected = array();

	public function injectSeo(ISeoServiceFactory $seoFactory) {

		$this->seoFactory = $seoFactory;
	}

	public function inject(\Nette\DI\Container $dic) {
		$this->rentalTypeRepositoryAccessor = $dic->rentalTypeRepositoryAccessor;
		$this->rentalTagRepositoryAccessor = $dic->rentalTagRepositoryAccessor;
		// $this->rentalRepositoryAccessor = $dic->rentalRepositoryAccessor;
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

		// $template->criteriaRentalType = $this->getRentalTypeCriteria();
		$template->criteriaRentalTag = $this->getRentalTagCriteria();

		$template->render();
	}

	protected function setSelectedCriteria() {

		return $this->selected = array(
			'rentalTag' => $this->presenter->getParameter('rentalTag'),
			'location' => $this->presenter->getParameter('location'),
			'rentalType' => $this->presenter->getParameter('rentalType'),
		);

	}

	protected function getRentalTagCriteria() {

		$links = array();
		$selected = $this->getSelectedParams();

		$rentalTypes = $this->rentalTagRepositoryAccessor->get()->findAll();
		foreach ($rentalTypes as $rentalType) {
			$params = array_merge($selected, array('rentalType' => $rentalType));

			$links[] = array(
				'entity' => $rentalType,
				'uri' => $this->presenter->link('//Rental:list', $params),
				'count' => 0
			);
		}

		return $links;

	}

	protected function getRentalTypeCriteria() { 

		$links = array();
		$selected = $this->getSelectedParams();

		$rentalTypes = $this->rentalTypeRepositoryAccessor->get()->findAll();
		foreach ($rentalTypes as $rentalType) {
			$params = array_merge($selected, array('rentalType' => $rentalType));

			$links[] = array(
				'entity' => $rentalType,
				'uri' => $this->presenter->link('//Rental:list', $params)
			);
		}

		return $links;

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
