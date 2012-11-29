<?php

namespace FrontModule;

class RegistrationPresenter extends BasePresenter {

	public function createComponentTabControl($name) {

		$tabBar = new \BaseModule\Components\TabControl\TabControl();

		$content = new \FrontModule\Components\Rentals\TopRentals($this->rentalRepository);
		$tab = $tabBar->addTab('top');
		$tab->setHeading(806);
		$tab->setContent($content);

		return $tabBar;

	}

}
