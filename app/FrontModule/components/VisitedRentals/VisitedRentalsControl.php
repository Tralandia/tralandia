<?php
namespace FrontModule\Components\VisitedRentals;

use Environment\Environment;
use Service\Rental\RentalSearchService;

class VisitedRentalsControl extends \BaseModule\Components\BaseControl {


	/**
	 * @var \VisitedRentals
	 */
	private $visitedRentals;


	public function __construct(\VisitedRentals $visitedRentals)
	{
		$this->visitedRentals = $visitedRentals;
	}


	public function render()
	{
		$template = $this->template;

		$this->presenter->fillTemplateWithCacheOptions($template);


		$rentalsIds = $this->visitedRentals->getSeen(10);

		$template->rentals = $rentalsIds;
		$template->findRental = $this->presenter->findRentalData;
		$template->totalCount = $this->visitedRentals->getCount();

		$template->render();
	}

}
