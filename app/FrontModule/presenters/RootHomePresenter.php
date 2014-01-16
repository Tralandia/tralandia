<?php

namespace FrontModule;

class RootHomePresenter extends BasePresenter {




	public function renderDefault() {

		if($this->device->isMobile()){
			$this->setView('mobileDefault');
			$this->setLayout('layoutMobile');
		}

		$this->template->rentals = $this->getRentals;
		$this->template->isRootHome = TRUE;
		$this->template->locationRentalsCount = $this->getLocationRentalsCount;
		$this->template->isRentalFeatured = $this->isRentalFeatured;
	}

	public function getRentals()
	{
		$rentals = $this->rentals->getFeaturedRentals($this->contextParameters['rentalCountOnRootHome']);

		return $rentals;
	}

	public function getLocationRentalsCount()
	{
		$counts = $this->rentals->getCounts(NULL, \Entity\Rental\Rental::STATUS_LIVE);
		return array_sum($counts);
	}

}
