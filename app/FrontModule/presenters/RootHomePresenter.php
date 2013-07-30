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

	/**
	 * @autowire
	 * @var \Extras\Translator
	 */
	protected $translator;

	/**
	 * @var \Extras\Models\Repository\RepositoryAccessor
	 */
	public $rentalRepositoryAccessor;

	public function injectBaseRepositories(\Nette\DI\Container $dic) {
		$this->rentalRepositoryAccessor = $dic->rentalRepositoryAccessor;
	}

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
		$featuredIds = $this->rentalRepositoryAccessor->get()->getFeaturedRentals(135);

		$rentals = array();
		foreach ($featuredIds as $rental) {
			$rentals[$rental->id]['service'] = $this->rentalDecoratorFactory->create($rental);
			$rentals[$rental->id]['entity'] = $rental;
		}

		return $rentals;
	}

	public function getLocationRentalsCount()
	{
		$counts = $this->rentalRepositoryAccessor->get()->getCounts(NULL, TRUE);
		return array_sum($counts);
	}

}
