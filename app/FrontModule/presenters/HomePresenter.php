<?php

namespace FrontModule;

use Model\Rental\IRentalDecoratorFactory;

class HomePresenter extends BasePresenter {

	public $rentalDecoratorFactory;

	public function injectDecorators(IRentalDecoratorFactory $rentalDecoratorFactory) {
		$this->rentalDecoratorFactory = $rentalDecoratorFactory;
	}


	public function renderDefault() {


		$rentalsEntities = $this->rentalRepositoryAccessor->get()->findAll();	

		$rentals = array();

		foreach ($rentalsEntities as $rental){
			$rentals[$rental->id]['service'] = $this->rentalDecoratorFactory->create($rental);			
			$rentals[$rental->id]['entity'] = $rental;
		}

		$regions = $this->locationRepositoryAccessor->get()->findBy(array(
				'parent' => 58
			), null , 50);

		$this->template->regions = array_chunk($regions,ceil(count($regions)/3));
		$this->template->rentals = $rentals;

	}

	public function createComponentCountryMap($name) {

		return new \FrontModule\Components\CountryMap\CountryMap($this->locationRepository, $this->locationTypeRepository);

	}

	public function createComponentTabControl($name) {

		$tabBar = new \BaseModule\Components\TabControl\TabControl();

/*		$content = new \FrontModule\Components\Rentals\TopRentals($this->rentalRepository);
		$tab = $tabBar->addTab('top');
		$tab->setHeading(806);
		$tab->setContent($content);

		$content = new \FrontModule\Components\RegionsPage\Regions($this->locationRepository, $this->locationTypeRepository);
		$tab = $tabBar->addTab('regions');
		$tab->setHeading(678);
		$tab->setContent($content)->setActive();

		$content = new \FrontModule\Components\LocalitiesPage\Localities($this->locationRepository, $this->locationTypeRepository);
		$tab = $tabBar->addTab('localities');
		$tab->setHeading(725);
		$tab->setContent($content);

		$content = new \FrontModule\Components\TagsPage\Tags();
		$tab = $tabBar->addTab('tags');
		$tab->setHeading(727);
		$tab->setContent($content);

		$content = new \FrontModule\Components\GenericPage\Generic();
		$content->slug = 'about';
		$tab = $tabBar->addTab('about');
		$tab->setHeading(1163);
		$tab->setContent($content);
*/
		return $tabBar;

	}

}
