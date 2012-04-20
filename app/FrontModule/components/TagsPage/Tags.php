<?php 
namespace FrontModule\Components\TagsPage;

use Nette\Application\UI\Control,
	FrontModule\Components\BasePage\BasePage;

class Tags extends \BaseModule\Components\BaseControl {

	public function render() {

		$group = \Service\Rental\Amenity\Group::getBySlug('tag');
		$this->template->tags = \Service\Rental\Amenity\AmenityList::getByGroup($group);

		$template = $this->template;
		$template->setFile(dirname(__FILE__) . '/page.latte');
		$template->render();

	}

}