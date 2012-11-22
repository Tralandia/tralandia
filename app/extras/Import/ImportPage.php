<?php

namespace Extras\Import;


class ImportPage extends BaseImport {

	protected $pages = array(
		array('destination' => ':Front:Sign:up', 'hash' => ''),
		array('destination' => ':Front:Sign:in', 'hash' => ''),
		array('destination' => ':Front:Sign:out', 'hash' => ''),
		array('destination' => ':Front:Homepage:', 'hash' => ''),
		array('destination' => ':Front:Attraction:detail', 'hash' => '/attraction'),
		array('destination' => ':Front:Rental:detail', 'hash' => '/rental'),
		# serach
		array('destination' => ':Front:Rental:list', 'hash' => '/location'),
		array('destination' => ':Front:Rental:list', 'hash' => '/location/objectType'),
		array('destination' => ':Front:Rental:list', 'hash' => '/location/objectType/tagBefore'),
		array('destination' => ':Front:Rental:list', 'hash' => '/location/objectType/tagAfter'),
		array('destination' => ':Front:Rental:list', 'hash' => '/objectType'),
		array('destination' => ':Front:Rental:list', 'hash' => '/objectType/tagBefore'),
		array('destination' => ':Front:Rental:list', 'hash' => '/objectType/tagAfter'),
		array('destination' => ':Front:Rental:list', 'hash' => '/tagBefore'),
		array('destination' => ':Front:Rental:list', 'hash' => '/tagAfter'),
		array('destination' => ':Front:Rental:list', 'hash' => '/location/tagBefore'),
		array('destination' => ':Front:Rental:list', 'hash' => '/location/tagAfter'),
		array('destination' => ':Front:Rental:list', 'hash' => '/attractionType'),
		array('destination' => ':Front:Rental:list', 'hash' => '/attractionType/location'),
	);

	public function doImport($subsection = NULL) {
		$context = $this->context;
		$model = $this->model;

		$nameType = $this->createPhraseType('\Page', 'name', 'ACTIVE');
		$titlePatternType = $this->createPhraseType('\Page', 'titlePattern', 'ACTIVE');
		$h1PatternType = $this->createPhraseType('\Page', 'h1Pattern', 'ACTIVE');

		foreach ($this->pages as $pageData) {
			$page = $context->pageRepository->findOneBy(array('destination' => $pageData['destination'], 'hash' => $pageData['hash']));
			if(!$page) {
				$page = $context->pageEntityFactory->create();
				$page->name = $this->createNewPhrase($nameType);
				$page->titlePattern = $this->createNewPhrase($titlePatternType);
				$page->h1Pattern = $this->createNewPhrase($h1PatternType);
			}
			$page->destination = $pageData['destination'];
			$page->hash = $pageData['hash'];
			if(array_key_exists('parameters', $pageData)) $page->parameters = $pageData['parameters'];
			$model->persist($page);
		}
		$model->flush();

	}
}