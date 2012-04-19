<?php 
namespace FrontModule\Components\CountryMap;

use Nette\Application\UI\Control;

class CountryMap extends Control {

	public function render() {

		$country = \Service\Location\Location::get(56);
		$this->template->regions = $this->getRegions($country);

	    $template = $this->template;
	    $template->setFile(dirname(__FILE__) . '/map.latte');
	    $template->setTranslator($this->presenter->getService('translator'));
	    $template->render();

	}

	private function getRegions($country) {
		$list = array();
		foreach (\Service\Location\LocationList::getBy(array('parentId'=>$country, 'type'=>6)) as $location) {
			if (isset($location->clickMapData->coords, $location->clickMapData->css)) {
				$list[] = $location;
			}
 		}
		return $list;
	}

}