<?php
namespace FrontModule\Components\CountryMap;

use Nette\Application\UI\Control;

class CountryMap extends \BaseModule\Components\BaseControl {

	public $locationRepository;
	public $locationTypeRepository;

	public function __construct($locationRepository, $locationTypeRepository) {

		$this->locationRepository = $locationRepository;
		$this->locationTypeRepository = $locationTypeRepository;

		parent::__construct();

	}

	public function render() {

		$country = $this->locationRepository->findOneBySlug('slovakia');

		$clickMapData = $this->getClickMapData($country);

		$this->template->regions = $clickMapData['regions'];
		$this->template->mapBox = $clickMapData['mapBox'];
		$this->template->navigatorData = $this->getNavigatorData($country, $clickMapData);

	    $template = $this->template;
	    // $template->setFile(dirname(__FILE__) . '/map.latte');
	    $template->setFile(dirname(__FILE__) . '/mapGregor.latte');
	    $template->setTranslator($this->presenter->getService('translator'));
	    $template->render();

	}

	private function getNavigatorData($country, $clickMapData) {

		$navigatorData = array();

		$navigatorData['top'] = array(
			$this->locationRepository->find(1),
			$country
		);

		$navigatorData['otherCountries'] = array();
		if ($country->clickMapData) {
			foreach ($country->clickMapData['otherCountries'] as $countryId) {
				$navigatorData['otherCountries'][] = $this->locationRepository->find($countryId);
			}
		}

		return $navigatorData;

	}

	private function getClickMapData($country) {

		$list = \Nette\ArrayHash::from(array(
			'regions' => array(),
			'mapBox' => array()
		));

		$type = $this->locationTypeRepository->findBySlug('region');

		foreach ($this->locationRepository->findBy(array('parent'=>$country, 'type'=>$type)) as $key=>$location) {
			if (isset($location->clickMapData['coords'], $location->clickMapData['css'])) {
				$list['regions'][$key] = $location;
			}
			if (isset($location->clickMapData['mapBox'])) {
				$list['mapBox'][$key] = $location;
			}
 		}

		return $list;

	}

}
