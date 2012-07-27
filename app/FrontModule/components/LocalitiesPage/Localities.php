<?php 
namespace FrontModule\Components\LocalitiesPage;

use Nette\Application\UI\Control,
	FrontModule\Components\BasePage\BasePage;

class Localities extends \BaseModule\Components\BaseControl {

	public function render() {

		$environment = new \Extras\Environment;
		$country = $environment->getLocation();
		$type = \Service\Location\Type::getBySlug('locality');

		$localities = \Service\Location\LocationList::getBy(
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