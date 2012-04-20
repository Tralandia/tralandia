<?php 
namespace FrontModule\Components\LocalitiesPage;

use Nette\Application\UI\Control,
	FrontModule\Components\BasePage\BasePage;

class Localities extends \BaseModule\Components\BaseControl {

	public function render() {

		$environment = new \Extras\Environment;
		$country = $environment->getLocation();
		$type = \Service\Location\Type::getBySlug('locality');

		$this->template->localities = \Service\Location\LocationList::getBy(array('parentId'=>$country, 'type'=>$type));

		$template = $this->template;
		$template->setFile(dirname(__FILE__) . '/page.latte');
		$template->render();

	}

}