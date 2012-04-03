<?php

namespace FrontModule;

class HomePresenter extends BasePresenter {
	
	public function createComponentCountryMap($name) {

		return new \FrontModule\CountryMap($this, $name);

	}

	public function createComponentTabBar($name) {

		$tabBar = new \FrontModule\TabBar($this, $name);

		$tabBar->addTab(\Service\Dictionary\Phrase::get(1), 'tab1');
		$tabBar->addTab(\Service\Dictionary\Phrase::get(2),	'tab2');
		$tabBar->addTab(\Service\Dictionary\Phrase::get(3),	'tab3');

		$tabBar->setActive(2);

		return $tabBar;

	}

}
