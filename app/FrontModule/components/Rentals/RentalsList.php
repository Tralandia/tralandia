<?php 
namespace FrontModule\Components\Rentals;

use Nette\Application\UI\Control,
	FrontModule\Components\BasePage\BasePage;

class RentalsList extends \BaseModule\Components\BaseControl {

	public function render() {

		$rentals = \Service\Rental\RentalList::getBy(array(
				
			),
			null,
			15
		);

		$this->template->rentals = $rentals->getIteratorAsServices('\Service\Rental\Rental');

		$template = $this->template;
		$template->setFile(dirname(__FILE__) . '/list.latte');
		$template->render();

	}

}