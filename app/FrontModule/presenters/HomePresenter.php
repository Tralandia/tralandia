<?php

namespace FrontModule;

class HomePresenter extends BasePresenter {

	public $locationTypeRepository;

	public function setContext() {

		$this->locationTypeRepository = $this->context->locationTypeRepository;
		parent::setContext();

	}

	protected function startup() {

		parent::startup();
		
	}

	public function renderDefault() {

		$vp = new \VisualPaginator($this, 'vp');
		$vp->templateFile = APP_DIR.'/FrontModule/components/VisualPaginator/paginator.latte';

		$paginator = $vp->getPaginator();
		$paginator->itemsPerPage = 15;
		$paginator->itemCount = 568;

	}

	public function createComponentCountryMap($name) {

		return new \FrontModule\Components\CountryMap\CountryMap($this->locationRepository, $this->locationTypeRepository);

	}

	public function createComponentTabControl($name) {

		$tabBar = new \BaseModule\Components\TabControl\TabControl();

		$content = new \FrontModule\Components\Rentals\TopRentals($this->rentalRepository);
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

		return $tabBar;

	}

}
