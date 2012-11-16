<?php 
namespace FrontModule\Components\RegionsPage;

use Nette\Application\UI\Control;

class Regions extends \BaseModule\Components\BaseControl {

	public $locationRepository;
	public $locationTypeRepository;

	public function __construct($locationRepository, $locationTypeRepository) {

		$this->locationRepository = $locationRepository;
		$this->locationTypeRepository = $locationTypeRepository;

		parent::__construct();

	}

	public function render() {

		$country = $this->locationRepository->findByType(3);
		debug($country);
		$type = $this->locationTypeRepository->findBySlug('region');

		$this->template->regions = $this->locationRepository->findBy(array('parent'=>$country, 'type'=>$type));

		// $rentalsCount = \Nette\ArrayHash::from(array());
		// foreach ($this->template->regions as $key=>$location) {
		// 	$service = \Service\Location\Location::get($location);
		// 	$rentalsCount[$key] = $service->getRentalsCount($location->id);
		// 	break;
		// }
		
		$template = $this->template;
		$template->setFile(dirname(__FILE__) . '/page.latte');
		$template->render();

	}

}