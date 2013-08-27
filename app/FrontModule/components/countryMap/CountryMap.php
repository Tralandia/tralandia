<?php 
namespace FrontModule\Components\CountryMap;

use Nette\Application\UI\Control;

class CountryMap extends \BaseModule\Components\BaseControl {

	public $locationRepositoryAccessor;
	public $locationTypeRepositoryAccessor;

	public function __construct($locationRepositoryAccessor, $locationTypeRepositoryAccessor) {

		$this->locationRepositoryAccessor = $locationRepositoryAccessor;
		$this->locationTypeRepositoryAccessor = $locationTypeRepositoryAccessor;

		parent::__construct();

	}

	public function render() {

		$country = $this->locationRepositoryAccessor->get()->findOneBySlug('slovakia');

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
			$this->locationRepositoryAccessor->get()->find(1),
			$country
		);

		$navigatorData['otherCountries'] = array();
		if ($country->clickMapData) {
			foreach ($country->clickMapData['otherCountries'] as $countryId) {
				$navigatorData['otherCountries'][] = $this->locationRepositoryAccessor->get()->find($countryId);
			}
		}

		return $navigatorData;

	}

	private function getClickMapData($country) {

		$list = \Nette\ArrayHash::from(array(
			'regions' => array(),
			'mapBox' => array()
		));

		$type = $this->locationTypeRepositoryAccessor->get()->findBySlug('region');

		foreach ($this->locationRepositoryAccessor->get()->findBy(array('parent'=>$country, 'type'=>$type)) as $key=>$location) {
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