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
	public $locationRepositoryAccessor;

	/**
	 * @var \Extras\Models\Repository\RepositoryAccessor
	 */
	public $rentalRepositoryAccessor;

	public function injectBaseRepositories(\Nette\DI\Container $dic) {
		$this->locationRepositoryAccessor = $dic->locationRepositoryAccessor;
		$this->rentalRepositoryAccessor = $dic->rentalRepositoryAccessor;
	}

	public function renderDefault() {
		$featuredIds = $this->rentalRepositoryAccessor->get()->getFeaturedRentals(135);

		$rentals = array();
		foreach ($featuredIds as $rental) {
			$rentals[$rental->id]['service'] = $this->rentalDecoratorFactory->create($rental);
			$rentals[$rental->id]['entity'] = $rental;
		}

		$this->template->rentals = $rentals;
	}

}
