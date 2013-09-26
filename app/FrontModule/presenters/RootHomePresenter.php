<?php

namespace FrontModule;
use Model\Rental\IRentalDecoratorFactory;
use Routers\FrontRoute;

class RootHomePresenter extends BasePresenter {

	/**
	 * @autowire
	 * @var \Model\Rental\IRentalDecoratorFactory
	 */
	protected $rentalDecoratorFactory;



	public function renderDefault() {

		if($this->device->isMobile()){
			$this->setView('mobileDefault');
			$this->setLayout('layoutMobile');
		}

		$this->template->rentals = $this->getRentals;
		$this->template->isRootHome = TRUE;
		$this->template->locationRentalsCount = $this->getLocationRentalsCount;
	}

	public function getRentals()
	{
		$featuredIds = $this->rentalDao->getFeaturedRentals($this->contextParameters['rentalCountOnRootHome']);

		$rentals = array();
		foreach ($featuredIds as $rental) {
			$rentals[$rental->id]['service'] = $this->rentalDecoratorFactory->create($rental);
			$rentals[$rental->id]['entity'] = $rental;
		}

		return $rentals;
	}

	public function getLocationRentalsCount()
	{
		$counts = $this->rentalDao->getCounts(NULL, TRUE);
		return array_sum($counts);
	}

}
