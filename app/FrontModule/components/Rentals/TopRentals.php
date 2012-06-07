<?php 
namespace FrontModule\Components\Rentals;

use Nette\Application\UI\Control,
	FrontModule\Components\BasePage\BasePage;

class TopRentals extends \BaseModule\Components\BaseControl {

	public function render() {

		$topRentals = \Service\Rental\Rental::getBy(array(
				
			),
			null,
			15
		);

		$template = $this->template;
		$template->setFile(dirname(__FILE__) . '/top.latte');
		$template->render();

	}

}