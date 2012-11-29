<?php

namespace Service\Seo;

use Nette, Extras, Service, Doctrine, Entity;
use Nette\Utils\Strings;

/**
 * @author Dávid Ďurika
 */
class SeoService extends Service\BaseService {
	
	protected $request;
	protected $requestParameters;
	protected $page;
	protected $phraseDecoratorFactory;

	protected $replacements = array(
		'location' => array(
			'location',
			'name',
		),
		'locationLocative' => array(
			'location', 
			'name',
			array(
				'case' => \Entity\Language::LOCATIVE,
			),
		),
		'tagAfter' => array(
			'rentalTag',
			'name',
		),
		'tagBefore' => array(
			'rentalTag',
			'name',
		),
	);

	protected $defaultVariation = array(
		'plural' => \Entity\Language::DEFAULT_PLURAL,
		'gender' => \Entity\Language::DEFAULT_GENDER,
		'case' => \Entity\Language::DEFAULT_CASE,
	);

	public function inject(\Model\Phrase\IPhraseDecoratorFactory $phraseDecoratorFactory) {
		$this->phraseDecoratorFactory = $phraseDecoratorFactory;
	}

	public function __construct(Nette\Application\Request $request)
	{
		$this->request = $request;
		$this->requestParameters = $this->request->getParameters();
		$this->setPage($this->requestParameters['page']);
	}


	protected function setPage($page)
	{
		$this->page = $page;
	}

	public function getH1() {
		return $this->compilePattern($this->page->h1Pattern);
	}

	public function getTitle() {
		return $this->compilePattern($this->page->titlePattern);
	}

	public function getAnchorText() {
		return $this->getH1();
	}

	protected function compilePattern($pattern) {

		$phrase = $this->phraseDecoratorFactory->create($pattern);
		$patternTranslation = $phrase->getTranslationText($this->requestParameters['language'], TRUE);
		if (!$patternTranslation) {
			return NULL;
		}

		$variables = Strings::matchAll($patternTranslation, '/\[(?P<replacement>[a-zA-Z]+)\]/');
		
		$texts = array();
		foreach ($variables as $key => $value) {
			$replacement = $this->replacements[$value['replacement']];
			$phrase = $this->requestParameters[$replacement[0]]->{$replacement[1]};
			$phrase = $this->phraseDecoratorFactory->create($phrase);
			$translation = $phrase->getTranslation($this->requestParameters['language'], TRUE);

			$textKey = '['.$value['replacement'].']';
			if (array_key_exists(2, $replacement) && is_array($replacement[2])) {
				$variationPath = array_merge($this->defaultVariation, $replacement[2]);
				$texts[$textKey] = $translation->getVariation($variationPath['plural'], $variationPath['gender'], $variationPath['case']);
			} else {
				$texts[$textKey] = (string) $translation;
			}

		}

		return str_replace(array_keys($texts), array_values($texts), $patternTranslation);

	}

}