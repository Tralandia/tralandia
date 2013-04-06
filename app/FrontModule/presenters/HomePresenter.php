<?php

namespace FrontModule;
use Model\Rental\IRentalDecoratorFactory;

class HomePresenter extends BasePresenter {

	/**
	 * @autowire
	 * @var \Model\Rental\IRentalDecoratorFactory
	 */
	protected $rentalDecoratorFactory;

	/**
	 * @autowire
	 * @var \LastSeen
	 */
	protected $lastSeen;

	/**
	 * @autowire
	 * @var \Service\Rental\IRentalSearchServiceFactory
	 */
	protected $rentalSearchFactory;

	public function renderDefault() {

		$a = $this->locationRepositoryAccessor->get()->find(269);
		$t = $this->link(':Front:Home:default', ['primaryLocation' => $a]);
		d($t);

		$search = $this->rentalSearchFactory->create($this->environment->primaryLocation);
		$featuredIds = $search->getFeaturedRentals(99);

		$rentals = array();
		foreach ($featuredIds as $rental) {
			$rentals[$rental->id]['service'] = $this->rentalDecoratorFactory->create($rental);
			$rentals[$rental->id]['entity'] = $rental;
		}

		$this->template->rentals = $rentals;
		$this->template->lastSeenRentals = $this->lastSeen->getSeenRentals(12);
		$this->template->isHome = TRUE;

	}

	public function createComponentCountryMap($name) {

		return new \FrontModule\Components\CountryMap\CountryMap($this->locationRepository, $this->locationTypeRepository);

	}

}
