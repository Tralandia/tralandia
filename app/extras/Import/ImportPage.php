<?php

namespace Extras\Import;

use Nette\Utils\Arrays;


class ImportPage extends BaseImport {

	protected $pages = array(
		array(
			'destination' => ':Front:Sign:up', 
			'hash' => '',
			'h1Pattern' => array('sk' => 'ubytovanie'), 
			'titlePattern' => array('sk' => 'ubytovanie')
		),
		array(
			'destination' => ':Front:Sign:in', 
			'hash' => '',
			'h1Pattern' => array('sk' => 'ubytovanie'), 
			'titlePattern' => array('sk' => 'ubytovanie')
		),
		array(
			'destination' => ':Front:Sign:out', 
			'hash' => '',
			'h1Pattern' => array('sk' => 'ubytovanie'), 
			'titlePattern' => array('sk' => 'ubytovanie')
		),
		array(
			'destination' => ':Front:Homepage:', 
			'hash' => '',
			'h1Pattern' => array('sk' => 'ubytovanie'), 
			'titlePattern' => array('sk' => 'ubytovanie')
		),
		array(
			'destination' => ':Front:Attraction:detail', 
			'hash' => '/attraction',
			'h1Pattern' => array('sk' => 'ubytovanie'), 
			'titlePattern' => array('sk' => 'ubytovanie')
		),
		array(
			'destination' => ':Front:Rental:detail', 
			'hash' => '/rental',
			'h1Pattern' => array('sk' => 'ubytovanie'), 
			'titlePattern' => array('sk' => 'ubytovanie')
		),
		# serach
		array(
			'destination' => ':Front:Rental:list', 
			'hash' => '/location',
			'h1Pattern' => array('sk' => 'ubytovanie [locationLocative]'), 
			'titlePattern' => array('sk' => 'ubytovanie [location]')
		),
		array(
			'destination' => ':Front:Rental:list', 
			'hash' => '/location/objectType',
			'h1Pattern' => array('sk' => 'ubytovanie'), 
			'titlePattern' => array('sk' => 'ubytovanie')
		),
		array(
			'destination' => ':Front:Rental:list', 
			'hash' => '/location/objectType/tagBefore',
			'h1Pattern' => array('sk' => 'ubytovanie'), 
			'titlePattern' => array('sk' => 'ubytovanie')
		),
		array(
			'destination' => ':Front:Rental:list', 
			'hash' => '/location/objectType/tagAfter',
			'h1Pattern' => array('sk' => 'ubytovanie'), 
			'titlePattern' => array('sk' => 'ubytovanie')
		),
		array(
			'destination' => ':Front:Rental:list', 
			'hash' => '/objectType',
			'h1Pattern' => array('sk' => 'ubytovanie'), 
			'titlePattern' => array('sk' => 'ubytovanie')
		),
		array(
			'destination' => ':Front:Rental:list', 
			'hash' => '/objectType/tagBefore',
			'h1Pattern' => array('sk' => 'ubytovanie'), 
			'titlePattern' => array('sk' => 'ubytovanie')
		),
		array(
			'destination' => ':Front:Rental:list', 
			'hash' => '/objectType/tagAfter',
			'h1Pattern' => array('sk' => 'ubytovanie'), 
			'titlePattern' => array('sk' => 'ubytovanie')
		),
		array(
			'destination' => ':Front:Rental:list', 
			'hash' => '/tagBefore',
			'h1Pattern' => array('sk' => 'ubytovanie'), 
			'titlePattern' => array('sk' => 'ubytovanie')
		),
		array(
			'destination' => ':Front:Rental:list', 
			'hash' => '/tagAfter',
			'h1Pattern' => array('sk' => 'ubytovanie'), 
			'titlePattern' => array('sk' => 'ubytovanie')
		),
		array(
			'destination' => ':Front:Rental:list', 
			'hash' => '/location/tagBefore',
			'h1Pattern' => array('sk' => 'ubytovanie'), 
			'titlePattern' => array('sk' => 'ubytovanie')
		),
		array(
			'destination' => ':Front:Rental:list', 
			'hash' => '/location/tagAfter',
			'h1Pattern' => array('sk' => 'ubytovanie [location] [tagAfter]'), 
			'titlePattern' => array('sk' => 'ubytovanie [locationLocative] [tagAfter]')
		),
		array(
			'destination' => ':Front:Rental:list', 
			'hash' => '/attractionType',
			'h1Pattern' => array('sk' => 'ubytovanie'), 
			'titlePattern' => array('sk' => 'ubytovanie')
		),
		array(
			'destination' => ':Front:Rental:list', 
			'hash' => '/attractionType/location',
			'h1Pattern' => array('sk' => 'ubytovanie'), 
			'titlePattern' => array('sk' => 'ubytovanie')
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
