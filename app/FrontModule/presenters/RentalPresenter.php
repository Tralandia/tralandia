<?php

namespace FrontModule;

class RentalPresenter extends BasePresenter {

	protected $rental;

	public function actionDetail($rental) {

		if (!$rental) {
			throw new \Nette\InvalidArgumentException('$rental argument does not match with the expected value');
		}
		$this->rental = \Service\Rental\Rental::get($rental);

		$this->template->rental = $this->rental;
		$this->template->amenities = $this->getAmenities($this->rental);
		$this->template->contacts = $this->separateContacts($this->rental);

	}

	public function actionList() {

		

	}

	private function getAmenities($rental, $limit = 15) {

		$i=1;
		$amenities = array();
		foreach ($this->rental->amenities as $amenity) {
			$amenities[] = $amenity;
			if ($i >= $limit) break;
			$i++;
		}

		return $amenities;

	}

	private function separateContacts($rental) {

		$contacts = $rental->contacts->getByType();
		debug($contacts);
		return $contacts;

	}

	// COMPONENTS
	
	public function createComponentListControl($name) {

		$tabBar = new \BaseModule\Components\TabControl\TabControl($this, $name);

		$t = $tabBar->addTab('rentals');
		$content = new \FrontModule\Components\Rentals\RentalsList($this, 'RentalsList');
		$t->setHeading(806)->setContent($content)->setActive();

		// $t = $tabBar->addTab('about');
		// $content = new \FrontModule\Components\RegionsPage\Regions($this, 'RegionsPage');
		// $t->setHeading(678)->setContent($content);

	}

	public function createComponentTabControl($name) {

		// Tabs contents
		$gallery 	= new \FrontModule\Components\Rentals\Gallery(
			$this, 
			'gallery', 
			$this->rental->media
		);
		
		$pricelist 	= new \FrontModule\Components\Rentals\Pricelists(
			$this, 
			'pricelists', 
			$this->rental->pricelists, 
			$this->context->environment->getLanguage()
		);

		// Tabs
		$tabBar = new \BaseModule\Components\TabControl\TabControl($this, $name);
		$tabBar->addTab('photos')
			->setHeading(806)
			->setActive()
			->setContent($gallery);
		$tabBar->addTab('prices')
			->setHeading(678)
			->setContent($pricelist);
		$tabBar->addTab('ammenities')
			->setHeading(725)
			->setContent('');
		$tabBar->addTab('map')
			->setHeading(727)
			->setContent('');

	}

}
