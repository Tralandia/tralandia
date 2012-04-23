<?php

namespace FrontModule;

class HomePresenter extends BasePresenter {
	
	public function renderDefault() {

		$vp = new \VisualPaginator($this, 'vp');
		$vp->templateFile = APP_DIR.'/FrontModule/components/VisualPaginator/paginator.latte';

		$paginator = $vp->getPaginator();
		$paginator->itemsPerPage = 15;
		$paginator->itemCount = 568;

		// TEST: saving file from URL
		$medium = \Service\Medium\Medium::createFromUrl('http://tralandia.local/temp/12486339913332.jpg');

	}

	public function createComponentCountryMap($name) {

		return new \FrontModule\Components\CountryMap\CountryMap($this, $name);

	}

	public function createComponentTabControl($name) {

		$tabBar = new \BaseModule\Components\TabControl\TabControl($this, $name);

		$t = $tabBar->addTab('top');
		$t->setHeader(806)->setContent('Top Objekty'); // @PHRASE: {_806, '1955 TOP holiday homes'}

		$t = $tabBar->addTab('regions');
		$content = new \FrontModule\Components\RegionsPage\Regions($this, 'RegionsPage');
		$t->setHeader(678)->setContent($content); // @PHRASE: {_678, '1112 Regions'}

		$t = $tabBar->addTab('localities');
		$content = new \FrontModule\Components\LocalitiesPage\Localities($this, 'LocalitiesPage');
		$t->setHeader(725)->setContent($content)->setActive(); // @PHRASE: {_725, '1626 Towns / Villages'}

		$t = $tabBar->addTab('tags');
		$content = new \FrontModule\Components\TagsPage\Tags($this, 'TagsPage');
		$t->setHeader(727)->setContent($content); // @PHRASE: {_727, '1628 Types of stays'}

		$t = $tabBar->addTab('about');
		$content = new \FrontModule\Components\GenericPage\Generic($this, 'GenericPage');
		$content->slug = 'about';
		$t->setHeader(1163)->setContent($content); // @PHRASE: {_1163, '23011 '}

	}

}
