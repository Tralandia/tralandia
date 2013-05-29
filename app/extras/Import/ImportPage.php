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
				'en' => 'Add your rental',
				'cs' => 'Registrace ubytovacího zařízení'
			), 
			'titlePattern' => array(
				'sk' => 'Registrácia', 
				'en' => 'Registration',
				'cs' => 'Registrace'
			),
			'name' => array(
				'sk' => 'Registrácia', 
				'en' => 'Registration',
				'cs' => 'Registrace'
			),
		),
		array(
			'destination' => ':Front:Sign:in', 
			'hash' => '',
			'h1Pattern' => array(
				'sk' => 'Prihlásenie', 
				'en' => 'Login',
				'cs' => 'Přihlásení'
			), 
			'titlePattern' => array(
				'sk' => 'Prihlásenie', 
				'en' => 'Login',
				'cs' => 'Přihlásení'
			),
			'name' => array(
				'sk' => 'Prihlásenie', 
				'en' => 'Login',
				'cs' => 'Přihlásení'
			),
		),
		array(
			'destination' => ':Front:Sign:out', 
			'hash' => '',
			'h1Pattern' => array(
				'sk' => 'Odhlásenie', 
				'en' => 'Logout',
				'cs' => 'Odhlásení'
			), 
			'titlePattern' => array(
				'sk' => 'Odhlásenie', 
				'en' => 'Logout',
				'cs' => 'Odhlásení'
			),
			'name' => array(
				'sk' => 'Odhlásenie', 
				'en' => 'Logout',
				'cs' => 'Odhlásení'
			)
		),
		array(
			'destination' => ':Front:CalendarIframe:default', 
			'hash' => '',
			'h1Pattern' => array(
				'sk' => 'Kalendár obsadenosti', 
				'en' => 'Occupancy calendar',
				'cs' => 'kalendář obsazenosti'
			), 
			'titlePattern' => array(
				'sk' => 'Kalendár obsadenosti', 
				'en' => 'Occupancy calendar',
				'cs' => 'kalendář obsazenosti'
			),
			'name' => array(
				'sk' => 'Kalendár obsadenosti', 
				'en' => 'Occupancy calendar',
				'cs' => 'kalendář obsazenosti'
			)
		),
		array(
			'destination' => ':Front:Home:default', 
			'hash' => '',
			'h1Pattern' => array(
				'sk' => 'Ubytovanie a Dovolenky [locationLocative]', 
				'en' => 'Accommodation in [location]',
				'cs' => 'Ubytování a Dovolené [locationLocative]'
			), 
			'titlePattern' => array(
				'sk' => 'Ubytovanie a Dovolenky [location]', 
				'en' => 'Accommodation [location]',
				'cs' => 'Ubytování a Dovolené [locationLocative]'
			)
		),
		array(
			'destination' => ':Front:Roothome:default',
			'hash' => '',
			'h1Pattern' => array(
				'sk' => 'Ubytovanie a Dovolenky na celom svete', 
				'en' => 'Worldwide rentals',
				'cs' => 'Ubytování a Dovolené na celém světě'
			), 
			'titlePattern' => array(
				'sk' => 'Ubytovanie a Dovolenky',
				'en' => 'Worldwide entals',
				'cs' => 'Ubytování a Dovolené'
			)
		),
		// array(
		// 	'destination' => ':Front:Attraction:detail', 
		// 	'hash' => '/attraction',
		// 	'h1Pattern' => array(
		// 		'sk' => '[attraction]', 
		// 		'en' => '[attraction]',
		// 	), 
		// 	'titlePattern' => array(
		// 		'sk' => '[attraction]', 
		// 		'en' => '[attraction]',
		// 	)
		// ),
		array(
			'destination' => ':Front:Rental:detail', 
			'hash' => '/rental',
			'h1Pattern' => array(
				'sk' => '[rental]', 
				'en' => '[rental]',
				'cs' => '[rental]'
			), 
			'titlePattern' => array(
				'sk' => '[rental], [location]', 
				'en' => '[rental], [location]',
				'cs' => '[rental], [location]'
			)
		),

		# search
		array(
			'destination' => ':Front:RentalList:default', 
			'hash' => '',
			'h1Pattern' => array(
				'sk' => 'Výsledky vyhľadávania', 
				'en' => 'Search results',
				'cs' => 'Výsledky vyhledávání'
			), 
			'titlePattern' => array(
				'sk' => 'Výsledky vyhľadávania', 
				'en' => 'Search results',
				'cs' => 'Výsledky vyhledávání'
			)
		),
		array(
			'destination' => ':Front:RentalList:default', 
			'hash' => '/location',
			'h1Pattern' => array(
				'sk' => 'Ubytovanie [locationLocative]', 
				'en' => '[location] rentals',
				'cs' => 'Ubytování [locationLocative]', 
			), 
			'titlePattern' => array(
				'sk' => 'Ubytovanie [location]', 
				'en' => 'Rentals in [location]',
				'cs' => 'Ubytování [locationLocative]', 
			)
		),
		array(
			'destination' => ':Front:RentalList:default', 
			'hash' => '/location/rentalType',
			'h1Pattern' => array(
				'sk' => '[rentalTypePlural] [locationLocative]', 
				'en' => '[rentalTypePlural] in [location]',
				'cs' => '[rentalTypePlural] [locationLocative]', 
			), 
			'titlePattern' => array(
				'sk' => '[rentalTypePlural] [location]', 
				'en' => '[rentalTypePlural] [location]',
				'cs' => '[rentalTypePlural] [locationLocative]', 
			)
		),
		array(
			'destination' => ':Front:RentalList:default', 
			'hash' => '/rentalType',
			'h1Pattern' => array(
				'sk' => '[rentalTypePlural] [locationLocative]', 
				'en' => '[rentalTypePlural] in [location]',
				'cs' => '[rentalTypePlural] [locationLocative]', 
			), 
			'titlePattern' => array(
				'sk' => '[rentalTypePlural] [location]', 
				'en' => '[rentalTypePlural] [location]',
				'cs' => '[rentalTypePlural] [locationLocative]', 
			)
		),
		array(
			'destination' => ':Front:RentalList:default', 
			'hash' => '/attractionType',
			'h1Pattern' => array(
				'sk' => '[attractionTypePlural] [locationLocative]', 
				'en' => '[attractionTypePlural] in [location]',
				'cs' => '[attractionTypePlural] [locationLocative]', 
			), 
			'titlePattern' => array(
				'sk' => '[attractionTypePlural] [location]', 
				'en' => '[attractionTypePlural] [location]',
				'cs' => '[attractionTypePlural] [locationLocative]', 
			)
		),
		array(
			'destination' => ':Front:RentalList:default', 
			'hash' => '/attractionType/location',
			'h1Pattern' => array(
				'sk' => '[attractionTypePlural] [locationLocative]', 
				'en' => '[attractionTypePlural] in [location]',
				'cs' => '[attractionTypePlural] [locationLocative]', 
			), 
			'titlePattern' => array(
				'sk' => '[attractionTypePlural] [location]', 
				'en' => '[attractionTypePlural] [location]',
				'cs' => '[attractionTypePlural] [locationLocative]', 
			)
		),
		array(
			'destination' => ':Front:Destination:default', 
			'hash' => '',
			'h1Pattern' => array(
				'sk' => 'Destinácie [locationLocative]', 
				'en' => 'Destinations in [location]',
				'cs' => 'Destinace [locationLocative]', 
			), 
			'titlePattern' => array(
				'sk' => 'Destinácie [locationLocative]', 
				'en' => 'Destinations in [location]',
				'cs' => 'Destinace [locationLocative]', 
			),
			'name' => array(
				'sk' => 'Destinácie', 
				'en' => 'Destinations',
				'cs' => 'Destinace', 
			), 
		),
		array(
			'destination' => ':Front:AboutUs:default', 
			'hash' => '',
			'h1Pattern' => array(
				'sk' => 'O projekte Tralandia', 
				'en' => 'About Tralandia',
				'cs' => 'O projekte Tralandia', 
			), 
			'titlePattern' => array(
				'sk' => 'O projekte Tralandia', 
				'en' => 'About Tralandia',
				'cs' => 'O projekte Tralandia', 
			),
			'name' => array(
				'sk' => 'O nás', 
				'en' => 'About us',
				'cs' => 'O nás', 
			), 
		),
		array(
			'destination' => ':Front:SupportUs:default', 
			'hash' => '',
			'h1Pattern' => array(
				'sk' => 'Podporte Tralandiu', 
				'en' => 'Support Tralandia',
				'cs' => 'Podpořte Tralandiu', 
			), 
			'titlePattern' => array(
				'sk' => 'Podporte Tralandiu', 
				'en' => 'Support Tralandia',
				'cs' => 'Podpořte Tralandiu', 
			),
			'name' => array(
				'sk' => 'Podporte Tralandiu', 
				'en' => 'Support Tralandia',
				'cs' => 'Podpořte Tralandiu', 
			), 
		),
		array(
			'destination' => ':Front:Faq:default', 
			'hash' => '',
			'h1Pattern' => array(
				'sk' => 'Časté otázky', 
				'en' => 'Frequently asked questions',
				'cs' => 'Časté otázky', 
			), 
			'titlePattern' => array(
				'sk' => 'Časté otázky', 
				'en' => 'Frequently asked questions',
				'cs' => 'Časté otázky', 
			),
			'name' => array(
				'sk' => 'Časté otázky', 
				'en' => 'Frequently asked questions',
				'cs' => 'Časté otázky', 
			), 
		),
		array(
			'destination' => ':Front:Tou:default', 
			'hash' => '',
			'h1Pattern' => array(
				'sk' => 'Podmienky používania', 
				'en' => 'Terms of use',
				'cs' => 'Podmínky používání', 
			), 
			'titlePattern' => array(
				'sk' => 'Podmienky používania', 
				'en' => 'Terms of use',
				'cs' => 'Podmínky používání', 
			),
			'name' => array(
				'sk' => 'Podmienky používania', 
				'en' => 'Terms of use',
				'cs' => 'Podmínky používání', 
			), 
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

			foreach ($page->name->getTranslations() as $translation) {
				$translation->translation = Arrays::get($pageData, array('name', $translation->language->iso), '');
			}

			$model->persist($page);
		}
		$model->flush();

	}
}
