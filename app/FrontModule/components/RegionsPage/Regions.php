<?php 
namespace FrontModule\Components\RegionsPage;

use Nette\Application\UI\Control,
	FrontModule\Components\BasePage\BasePage;

class Regions extends \BaseModule\Components\BaseControl {

	public function render() {

		$environment = new \Extras\Environment;
		$country = $environment->getLocation();
		$type = \Service\Location\Type::getBySlug('region');

		$this->template->regions = \Service\Location\LocationList::getBy(array('parentId'=>$country, 'type'=>$type));

		// $regions = \Nette\ArrayHash::from(array());
		// foreach (\Service\Location\LocationList::getBy(array('parentId'=>$country, 'type'=>$type)) as $key => $location) {
		// 	$regions[$key] = $location->getRentalsCount();
		// 	break;
		// }
		// debug($regions);
		
		$template = $this->template;
		$template->setFile(dirname(__FILE__) . '/page.latte');
		$template->render();

	}

}