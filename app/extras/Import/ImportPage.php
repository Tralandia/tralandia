<?php

namespace Extras\Import;

use Nette\Utils\Arrays;


class ImportPage extends BaseImport {

	protected $pages = array(
		array(
			'destination' => ':Front:Registration:default', 
			'hash' => '',
			'h1Pattern' => array(
				'sk' => 'Registrácia ubytovacieho zariadenia', 
				'en' => 'Add your rental'
			), 
			'titlePattern' => array(
				'sk' => 'Registrácia', 
				'en' => 'Registration'
			)
		),
		array(
			'destination' => ':Front:Sign:in', 
			'hash' => '',
			'h1Pattern' => array(
				'sk' => 'Prihlásenie', 
				'en' => 'Login'
			), 
			'titlePattern' => array(
				'sk' => 'Prihlásenie', 
				'en' => 'Login'
			)
		),
		array(
			'destination' => ':Front:Sign:out', 
			'hash' => '',
			'h1Pattern' => array(
				'sk' => 'Odhlásenie', 
				'en' => 'Logout'
			), 
			'titlePattern' => array(
				'sk' => 'Odhlásenie', 
				'en' => 'Logout'
			)
		),
		array(
			'destination' => ':Front:Home:default', 
			'hash' => '',
			'h1Pattern' => array(
				'sk' => 'Ubytovanie a Dovolenky [primaryLocationLocative]', 
				'en' => 'Accommodation in [primaryLocation]'
			), 
			'titlePattern' => array(
				'sk' => 'Ubytovanie a Dovolenky [primaryLocation]', 
				'en' => 'Accommodation [primaryLocation]'
			)
		),
		array(
			'destination' => ':Front:Roothome:default',
			'hash' => '',
			'h1Pattern' => array(
				'sk' => 'Ubytovanie a Dovolenky na celom svete', 
				'en' => 'Worldwide rentals'
			), 
			'titlePattern' => array(
				'sk' => 'Ubytovanie a Dovolenky',
				'en' => 'Worldwide entals'
			)
		),
		array(
			'destination' => ':Front:Attraction:detail', 
			'hash' => '/attraction',
			'h1Pattern' => array(
				'sk' => '[attraction]', 
				'en' => '[attraction]'
			), 
			'titlePattern' => array(
				'sk' => '[attraction]', 
				'en' => '[attraction]'
			)
		),
		array(
			'destination' => ':Front:Rental:detail', 
			'hash' => '/rental',
			'h1Pattern' => array(
				'sk' => '[rental]', 
				'en' => '[rental]'
			), 
			'titlePattern' => array(
				'sk' => '[rental], [location]', 
				'en' => '[rental], [location]'
			)
		),

		# search
		array(
			'destination' => ':Front:Rental:list', 
			'hash' => '/location',
			'h1Pattern' => array(
				'sk' => 'Ubytovanie [locationLocative]', 
				'en' => '[location] rentals'
			), 
			'titlePattern' => array(
				'sk' => 'Ubytovanie [location]', 
				'en' => 'Rentals in [location]'
			)
		),
		array(
			'destination' => ':Front:Rental:list', 
			'hash' => '/location/rentalType',
			'h1Pattern' => array(
				'sk' => '[rentalTypePlural] [locationLocative]', 
				'en' => '[rentalTypePlural] in [location]'
			), 
			'titlePattern' => array(
				'sk' => '[rentalTypePlural] [location]', 
				'en' => '[rentalTypePlural] [location]'
			)
		),
		array(
			'destination' => ':Front:Rental:list', 
			'hash' => '/location/rentalType/tagBefore',
			'h1Pattern' => array(
				'sk' => '[tag] [rentalTypePlural] [locationLocative]', 
				'en' => '[tag] [rentalTypePlural] in [location]'
			), 
			'titlePattern' => array(
				'sk' => '[tag] [rentalTypePlural] [location]', 
				'en' => '[tag] [rentalTypePlural] [location]'
			)
		),
		array(
			'destination' => ':Front:Rental:list', 
			'hash' => '/location/rentalType/tagAfter',
			'h1Pattern' => array(
				'sk' => '[rentalTypePlural] [tag] [locationLocative]', 
				'en' => '[rentalTypePlural] [tag] in [location]'
			), 
			'titlePattern' => array(
				'sk' => '[rentalTypePlural] [tag] [location]', 
				'en' => '[rentalTypePlural] [tag] [location]'
			)
		),
		array(
			'destination' => ':Front:Rental:list', 
			'hash' => '/rentalType',
			'h1Pattern' => array(
				'sk' => '[rentalTypePlural] [locationLocative]', 
				'en' => '[rentalTypePlural] in [location]'
			), 
			'titlePattern' => array(
				'sk' => '[rentalTypePlural] [location]', 
				'en' => '[rentalTypePlural] [location]'
			)
		),
		array(
			'destination' => ':Front:Rental:list', 
			'hash' => '/rentalType/tagBefore',
			'h1Pattern' => array(
				'sk' => '[tag] [rentalTypePlural] [locationLocative]', 
				'en' => '[tag] [rentalTypePlural] in [location]'
			), 
			'titlePattern' => array(
				'sk' => '[tag] [rentalTypePlural] [location]', 
				'en' => '[tag] [rentalTypePlural] [location]'
			)
		),
		array(
			'destination' => ':Front:Rental:list', 
			'hash' => '/rentalType/tagAfter',
			'h1Pattern' => array(
				'sk' => '[rentalTypePlural] [tag] [locationLocative]', 
				'en' => '[rentalTypePlural] [tag] in [location]'
			), 
			'titlePattern' => array(
				'sk' => '[rentalTypePlural] [tag] [location]', 
				'en' => '[rentalTypePlural] [tag] [location]'
			)
		),
		array(
			'destination' => ':Front:Rental:list', 
			'hash' => '/tagBefore',
			'h1Pattern' => array(
				'sk' => '[tag] ubytovanie [locationLocative]', 
				'en' => '[tag] rentals in [location]'
			), 
			'titlePattern' => array(
				'sk' => '[tag] ubytovanie [location]', 
				'en' => '[tag] rentals [location]f'
			)
		),
		array(
			'destination' => ':Front:Rental:list', 
			'hash' => '/tagAfter',
			'h1Pattern' => array(
				'sk' => 'Ubytovanie [tag] [locationLocative]', 
				'en' => 'Rentals [tag] in [location]'
			), 
			'titlePattern' => array(
				'sk' => 'Ubytovanie [tag] [location]', 
				'en' => 'Rentals [tag] [location]'
			)
		),
		array(
			'destination' => ':Front:Rental:list', 
			'hash' => '/location/tagBefore',
			'h1Pattern' => array(
				'sk' => '[tag] ubytovanie [locationLocative]', 
				'en' => '[tag] rentals in [location]'
			), 
			'titlePattern' => array(
				'sk' => '[tag] ubytovanie [location]', 
				'en' => '[tag] rentals [location]f'
			)
		),
		array(
			'destination' => ':Front:Rental:list', 
			'hash' => '/location/tagAfter',
			'h1Pattern' => array(
				'sk' => 'Ubytovanie [tag] [locationLocative]', 
				'en' => 'Rentals [tag] in [location]'
			), 
			'titlePattern' => array(
				'sk' => 'Ubytovanie [tag] [location]', 
				'en' => 'Rentals [tag] [location]'
			)
		),
		array(
			'destination' => ':Front:Rental:list', 
			'hash' => '/attractionType',
			'h1Pattern' => array(
				'sk' => '[attractionTypePlural] [locationLocative]', 
				'en' => '[attractionTypePlural] in [location]'
			), 
			'titlePattern' => array(
				'sk' => '[attractionTypePlural] [location]', 
				'en' => '[attractionTypePlural] [location]'
			)
		),
		array(
			'destination' => ':Front:Rental:list', 
			'hash' => '/attractionType/location',
			'h1Pattern' => array(
				'sk' => '[attractionTypePlural] [locationLocative]', 
				'en' => '[attractionTypePlural] in [location]'
			), 
			'titlePattern' => array(
				'sk' => '[attractionTypePlural] [location]', 
				'en' => '[attractionTypePlural] [location]'
			)
		),
	);

	public function doImport($subsection = NULL) {
		$context = $this->context;
		$model = $this->model;

		$nameType = $this->createPhraseType('\Page', 'name', 'ACTIVE');
		$titlePatternType = $this->createPhraseType('\Page', 'titlePattern', 'ACTIVE');
		$h1PatternType = $this->createPhraseType('\Page', 'h1Pattern', 'ACTIVE');

		foreach ($this->pages as $pageData) {
			$page = $context->pageRepositoryAccessor->get()->findOneBy(array('destination' => $pageData['destination'], 'hash' => $pageData['hash']));
			if(!$page) {
				$page = $context->pageEntityFactory->create();
				$page->name = $this->createNewPhrase($nameType);
				$page->titlePattern = $this->createNewPhrase($titlePatternType);
				$page->h1Pattern = $this->createNewPhrase($h1PatternType);
			}
			$page->destination = $pageData['destination'];
			$page->hash = $pageData['hash'];

			if(array_key_exists('parameters', $pageData)) $page->parameters = $pageData['parameters'];

			foreach ($page->titlePattern->getTranslations() as $translation) {
				$translation->translation = Arrays::get($pageData, array('titlePattern', $translation->language->iso), '');
			}

			foreach ($page->h1Pattern->getTranslations() as $translation) {
				$translation->translation = Arrays::get($pageData, array('h1Pattern', $translation->language->iso), '');
			}

			$model->persist($page);
		}
		$model->flush();

	}
}
