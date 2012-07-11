<?php

namespace FrontModule;

class RentalPresenter extends BasePresenter {

	private $rental;

	public function actionDefault($slug) {

		if (!$slug) {
			throw new \Nette\InvalidArgumentException('$slug argument does not match with the expected value');
		}
		$this->rental = \Service\Rental\Rental::getBySlug($slug);
debug($this->rental->pricelists);
		$this->template->rental = $this->rental;
		$this->template->amenities = $this->getAmenities();
		$this->template->contacts = $this->separateContacts();

	}

	private function getAmenities($limit = 15) {

		$i=1;
		$amenities = array();
		foreach ($this->rental->amenities as $amenity) {
			$amenities[] = $amenity;
			if ($i >= $limit) break;
			$i++;
		}

		return $amenities;

	}

	private function separateContacts($limit = 15) {

		$contacts = array(
			'phones' => array(),
			'url' => array(),
			'names' => array(),
			'emails' => array()
		);

		foreach ($this->rental->contacts->list as $contact) {
			if ($contact instanceof \Extras\Types\Phone) {
				if ($contact->original) {
					$contacts['phones'][] = $contact->original;
				}
			} else if ($contact instanceof \Extras\Types\Email) {
				$contacts['emails'][] = $contact->data;
			} else if ($contact instanceof \Extras\Types\Url) {
				if ($contact->host) {
					$contacts['url'][] = $contact->scheme . '://' . $contact->host;
				}
			} else if ($contact instanceof \Extras\Types\Name) {
				if ($contact->first || $contact->middle || $contact->last) {
					$contacts['names'][] = $contact->first .' '. $contact->middle .' '. $contact->last;
				}
			}
		}

		return $contacts;

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
