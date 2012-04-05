<?php

namespace FrontModule;

class HomePresenter extends BasePresenter {
	
	public function createComponentCountryMap($name) {

		return new \FrontModule\CountryMap($this, $name);

	}

	public function createComponentTabControl($name) {

		$tabBar = new \FrontModule\TabControl\TabControl($this, $name);

		$t = $tabBar->addTab('tab1');
		$t->header = 1;
		$t->active = true;
		$t->content = 'content 1';

		$t = $tabBar->addTab('tab2');
		$t->header = 2;
		$t->content = 'content 2';

		$t = $tabBar->addTab('tab3');
		$t->header = 3;
		$t->content = 'content 3';

		return $tabBar;

	}

}
