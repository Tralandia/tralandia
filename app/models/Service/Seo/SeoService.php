<?php

namespace Service\Seo;

use Nette, Extras, Service, Doctrine, Entity;
use Nette\Utils\Strings;

/**
 * @author Dávid Ďurika
 */
class SeoService extends Service\BaseService {
	
	protected $request;
	protected $page;
	protected $phraseDecoratorFactory;

	protected $replacements = array(
		'location' => array(
			'location',
		),
		'locationLocative' => array(
			'location', 
			array(
				'case' => \Entity\Language::LOCATIVE,
			),
		),
	);

	public function inject(\Model\Phrase\IPhraseDecoratorFactory $phraseDecoratorFactory) {
		$this->phraseDecoratorFactory = $phraseDecoratorFactory;
	}

	public function __construct(Nette\Application\Request $request)
	{
		$this->request = $request;
		$this->setPage($this->request->getParameters()->page);
	}


	protected function setPage($page)
	{
		$this->page = $page;
	}

	public function getH1() {
		$phrase = $this->phraseDecoratorFactory->create($this->page->h1Pattern);

		$translation = $phrase->getTranslationText($this->request->getParameters()->language, TRUE);
		if (!$translation) {
			return NULL;
		}

		$variables = Strings::matchAll($translation, '/\[[a-zA-Z]+\]/');
		d($variables);

	}

}