<?php

namespace FrontModule;

class HomePresenter extends BasePresenter {
	
	public function renderDefault() {

		$vp = new \VisualPaginator($this, 'vp');
		$vp->templateFile = APP_DIR.'/FrontModule/components/VisualPaginator/paginator.latte';

		$paginator = $vp->getPaginator();
		$paginator->itemsPerPage = 15;
		$paginator->itemCount = 568;

	}

	public function createComponentCountryMap($name) {

		return new \FrontModule\Components\CountryMap\CountryMap($this, $name);

	}

	public function createComponentTabControl($name) {

		$tabBar = new \BaseModule\Components\TabControl\TabControl($this, $name);

		$t = $tabBar->addTab('top');
		$content = new \FrontModule\Components\Rentals\TopRentals($this, 'TopRentals');
		$t->setHeading(806)->setContent($content)->setActive();

		$t = $tabBar->addTab('regions');
		$content = new \FrontModule\Components\RegionsPage\Regions($this, 'RegionsPage');
		$t->setHeading(678)->setContent($content);

		$t = $tabBar->addTab('localities');
		$content = new \FrontModule\Components\LocalitiesPage\Localities($this, 'LocalitiesPage');
		$t->setHeading(725)->setContent($content);

		$t = $tabBar->addTab('tags');
		$content = new \FrontModule\Components\TagsPage\Tags($this, 'TagsPage');
		$t->setHeading(727)->setContent($content);

		$t = $tabBar->addTab('about');
		$content = new \FrontModule\Components\GenericPage\Generic($this, 'GenericPage');
		$content->slug = 'about';
		$t->setHeading(1163)->setContent($content);

	}

}
