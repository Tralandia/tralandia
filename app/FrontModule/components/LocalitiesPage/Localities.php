<?php 
namespace FrontModule\Components\LocalitiesPage;

use Nette\Application\UI\Control,
	FrontModule\Components\BasePage\BasePage;

class Localities extends \BaseModule\Components\BaseControl {

	public $locationRepository;
	public $locationTypeRepository;

	public function __construct($locationRepository, $locationTypeRepository) {

		$this->locationRepository = $locationRepository;
		$this->locationTypeRepository = $locationTypeRepository;

		parent::__construct();

	}

	public function render() {

		$country = $this->locationRepository->find(52);
		$type = $this->locationTypeRepository->findBySlug('locality');

		$localities = $this->locationRepository->findBy(
			array(
				'parent' => $country,
				'type' => $type,
			)
		);

		$l = array();
		foreach ($localities as $key => $locality) {
			$l[$key] = \Nette\ArrayHash::from(array(
				'name' => $locality->name,
				'slug' => $locality->slug,
				'rentalsCount' => count($locality->rentals),
			));
		}

		$this->template->localities = \Tools::reorganizeArray($l, 3);

		$template = $this->template;
		$template->setFile(dirname(__FILE__) . '/page.latte');
		$template->render();

	}

}