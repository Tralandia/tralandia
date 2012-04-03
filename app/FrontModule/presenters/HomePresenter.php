<?php

namespace FrontModule;

class HomePresenter extends BasePresenter {
	
	public function createComponentCountryMap($name) {

		return new \FrontModule\CountryMap($this, $name);

	}

	public function createComponentTabsBar($name) {

		$tabBar = new \FrontModule\TabsBar($this, $name);

		$tabBar->addTab(\Service\Dictionary\Phrase::get(1), 'tab1', 'content1');
		$tabBar->addTab(\Service\Dictionary\Phrase::get(2), 'tab2', 'content2');
		$tabBar->addTab(\Service\Dictionary\Phrase::get(3), 'tab3', 'content3');

		$tabBar->setActive('tab1');

		return $tabBar;

	}

}
