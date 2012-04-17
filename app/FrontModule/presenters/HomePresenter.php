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
		$medium = \Service\Medium\Medium::createFromUrl('http://tralandia.local/temp/Android%20Root.rar');

	}

	public function createComponentCountryMap($name) {

		return new \FrontModule\Components\CountryMap\CountryMap($this, $name);

	}

	public function createComponentTabControl($name) {

		$tabBar = new \BaseModule\Components\TabControl\TabControl($this, $name);

		$t = $tabBar->addTab('top');
		$t->setHeader(10)->setContent('Top Objekty')->setActive();

		$t = $tabBar->addTab('regions');
		$t->setHeader(15)->setContent('Regiony');

		$t = $tabBar->addTab('localities');
		$t->setHeader(12)->setContent('Mesta/Obce');

		$t = $tabBar->addTab('tags');
		$t->setHeader(13)->setContent('Typy pobytov');

		$t = $tabBar->addTab('about');
		$t->setHeader(14)->setContent('O nas');

		return $tabBar;

	}

}
