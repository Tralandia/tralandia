<?php 
namespace FrontModule\Components\CountryMap;

use Nette\Application\UI\Control;

class CountryMap extends \BaseModule\Components\BaseControl {

	public function render() {

		$environment = new \Extras\Environment;
		$country = $environment->getLocation();

		$clickMapData = $this->getClickMapData($country);

		$this->template->regions = $clickMapData['regions'];
		$this->template->mapBox = $clickMapData['mapBox'];
		$this->template->navigatorData = $this->getNavigatorData($country, $clickMapData);

	    $template = $this->template;
	    $template->setFile(dirname(__FILE__) . '/map.latte');
	    $template->setTranslator($this->presenter->getService('translator'));
	    $template->render();

	}

	private function getNavigatorData($country, $clickMapData) {

		$navigatorData = array();

		$navigatorData['top'] = array(
			\Service\Location\Location::getBySlug('world'),
			$this->presenter->context->environment->getLocation()
		);

		$navigatorData['otherCountries'] = array();
		if ($country->clickMapData) {
			debug($country->clickMapData);
			foreach ($country->clickMapData['otherCountries'] as $countryId) {
				$navigatorData['otherCountries'][] = \Service\Location\Location::get($countryId);
			}
		}
		return $navigatorData;

	}

	private function getClickMapData($country) {

		$list = \Nette\ArrayHash::from(array(
			'regions' => array(),
			'mapBox' => array()
		));

		$type = \Service\Location\Type::getBySlug('region');

		foreach (\Service\Location\LocationList::getBy(array('parentId'=>$country, 'type'=>$type)) as $key=>$location) {
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