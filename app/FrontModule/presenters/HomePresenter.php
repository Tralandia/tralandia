<?php

namespace FrontModule;

class HomePresenter extends BasePresenter {
	
	public function renderDefault() {

		$vp = new \VisualPaginator($this, 'vp');
		$vp->templateFile = APP_DIR.'/FrontModule/components/VisualPaginator/paginator.latte';

		$paginator = $vp->getPaginator();
		$paginator->itemsPerPage = 15;
		$paginator->itemCount = 568;

		// $environment = new \Extras\Environment;
		// $location = $environment->getLocation();

		// $l = \Service\Location\Location::get($location->id);
		// $t = $l->fetchBranchAsArray(2);
		// foreach ($t as $node) {
		// 	echo($node->getLevel()." - $node");
		// }

	}

	public function createComponentCountryMap($name) {

		return new \FrontModule\Components\CountryMap\CountryMap($this, $name);

	}

	public function createComponentTabControl($name) {

		$tabBar = new \BaseModule\Components\TabControl\TabControl($this, $name);

		$t = $tabBar->addTab('top');
		$t->setHeader(806)->setContent('Top Objekty')->setActive(); // {_806, '1955 TOP holiday homes'}

		$t = $tabBar->addTab('regions');
		$t->setHeader(678)->setContent('Regiony'); // {_678, '1112 Regions'}

		$t = $tabBar->addTab('localities');
		$t->setHeader(725)->setContent('Mesta/Obce'); // {_725, '1626 Towns / Villages'}

		$t = $tabBar->addTab('tags');
		$t->setHeader(727)->setContent('Typy pobytov'); // {_727, '1628 Types of stays'}

		$t = $tabBar->addTab('about');
		$t->setHeader(1163)->setContent('O nas'); // {_1163, '23011 '}

		return $tabBar;

	}

}
