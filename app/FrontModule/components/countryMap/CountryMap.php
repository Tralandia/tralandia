<?php
namespace FrontModule\Components\CountryMap;

use Nette\Application\UI\Control;
use Tralandia\BaseDao;

class CountryMap extends \BaseModule\Components\BaseControl {

	/**
	 * @var \Tralandia\BaseDao
	 */
	public $locationDao;

	/**
	 * @var \Tralandia\BaseDao
	 */
	public $locationTypeDao;

	public function __construct(BaseDao $locationDao,BaseDao $locationTypeDao) {

		$this->locationDao = $locationDao;
		$this->locationTypeDao = $locationTypeDao;

		parent::__construct();

	}

	public function render() {

		$country = $this->locationDao->findOneBySlug('slovakia');

		$clickMapData = $this->getClickMapData($country);

		$this->template->regions = $clickMapData['regions'];
		$this->template->mapBox = $clickMapData['mapBox'];
		$this->template->navigatorData = $this->getNavigatorData($country, $clickMapData);

	    $template = $this->template;
	    $template->setFile(dirname(__FILE__) . '/newLayout.latte');
	    $template->setTranslator($this->presenter->getService('translator'));
	    $template->render();

	}

	private function getNavigatorData($country, $clickMapData) {

		$navigatorData = array();

		$navigatorData['top'] = array(
			$this->locationDao->find(1),
			$country
		);

		$navigatorData['otherCountries'] = array();
		if ($country->clickMapData) {
			foreach ($country->clickMapData['otherCountries'] as $countryId) {
				$navigatorData['otherCountries'][] = $this->locationDao->find($countryId);
			}
		}

		return $navigatorData;

	}

	private function getClickMapData($country) {

		$list = \Nette\ArrayHash::from(array(
			'regions' => array(),
			'mapBox' => array()
		));

		$type = $this->locationTypeDao->findBySlug('region');

		foreach ($this->locationDao->findBy(array('parent'=>$country, 'type'=>$type)) as $key=>$location) {
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
