<?php

namespace FrontModule;

class HomePresenter extends BasePresenter {
	
	public function createComponentCountryMap($name) {

		return new \FrontModule\CountryMap($this, $name);

	}

	public function createComponentTabControl($name) {

		$tabBar = new \FrontModule\TabControl\TabControl($this, $name);

		$t = $tabBar->addTab('top');
		$t->setHeader(10)->setActive(true)->setContent('Top Objekty');

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
