<?php

namespace Service\Seo;

use Nette, Extras, Service, Doctrine, Entity;
use Nette\Utils\Strings;

/**
 * @author Dávid Ďurika
 */
class SeoService extends Nette\Object {

	protected $request;
	protected $requestParameters;
	protected $page;
	protected $url;
	protected $phraseDecoratorFactory;
	protected $pageRepositoryAccessor;

	protected $replacements = array(
		'primaryLocation' => array(
			'primaryLocation', 
			'name', 
		),
		'primaryLocationLocative' => array(
			'primaryLocation',
			'name',
		),
		'location' => array(
			'location', // request parameter's name
			'name', // entity attribute
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
		'rentalTypePlural' => array(
			'rentalType',
			'name',
		)
	);

	protected $defaultVariation = array(
		'plural' => \Entity\Language::DEFAULT_PLURAL,
		'gender' => \Entity\Language::DEFAULT_GENDER,
		'case' => \Entity\Language::DEFAULT_CASE,
	);

	public function inject(\Model\Phrase\IPhraseDecoratorFactory $phraseDecoratorFactory) {
		$this->phraseDecoratorFactory = $phraseDecoratorFactory;
	}

	public function injectDic(\Nette\DI\Container $dic) {
		$this->pageRepositoryAccessor = $dic->pageRepositoryAccessor;
	}

	/**
	 * @param string                    $url
	 * @param Nette\Application\Request $request
	 */
	public function __construct($url, Nette\Application\Request $request)
	{
		$this->url = $url;
		$this->request = $request;
		$this->requestParameters = $this->request->getParameters();
	}

	/**
	 * Vrati url
	 * @return string
	 */
	public function getUrl() {
		return $this->url;
	}

	/**
	 * Retrun text for H1
	 * @return string
	 */
	public function getH1() {
		return $this->compilePattern($this->getPage()->h1Pattern);
	}

	/**
	 * Retrun text for Title
	 * @return string
	 */
	public function getTitle() {
		return $this->compilePattern($this->getPage()->titlePattern);
	}

	/**
	 * Retrun text for anchor text
	 * @return string
	 */
	public function getAnchorText() {
		return $this->getH1();
	}

	public function getPage() {
		if (!$this->page) {
			$destination = ':' . $this->request->getPresenterName() . ':' . $this->getParameter('action');
			if ($destination == ':Front:Rental:list') {
				$hash = array();
				foreach (\Routers\FrontRoute::$pathSegmentTypesById as $key => $value) {
					if ($key == 2) continue;
					if ($this->existsParameter($value)) {
						if ($value == 'rentalTag') {
							$tagName = $this->phraseDecoratorFactory->create($this->getParameter($value)->name);
							$tagTranslation = $tagName->getTranslation($this->getParameter('language'));
							if ($tagTranslation->position == \Entity\Phrase\Translation::BEFORE) {
								$value = \Entity\Phrase\Translation::BEFORE;
							} else {
								$value = \Entity\Phrase\Translation::AFTER;
							}
							$value = 'tag'.$value;
						}
						$hash[] = '/'.$value;
					}
				}
				$hash = implode('', $hash);
			} else {
				$hash = '';
			}
			$this->page = $this->pageRepositoryAccessor->get()->findOneBy(array('hash' => $hash, 'destination' => $destination));
		}
		return $this->page;
	}

	/**
	 * Return parameter by given name
	 * @param  string $name parameter name
	 * @return Entity\BaseEntity|string
	 */
	public function getParameter($name) {
		return $this->requestParameters[$name];
	}

	public function getParameters() {
		return $this->requestParameters;
	}

	/**
	 * Ceck if parameter exists
	 * @param  string $name parameter name
	 * @return boolean
	 */
	public function existsParameter($name) {
		return array_key_exists($name, $this->requestParameters);
	}

	public function &__get($name) {
		if($this->existsParameter($name)) {
			$parameter = $this->getParameter($name);
			return $parameter;
		}

		return parent::__get($name);
	}

	protected function compilePattern($pattern) {

		$phrase = $this->phraseDecoratorFactory->create($pattern);
		$patternTranslation = $phrase->getTranslationText($this->getParameter('language'), TRUE);
		if (!$patternTranslation) {
			return NULL;
		}

		$variables = Strings::matchAll($patternTranslation, '/\[(?P<replacement>[a-zA-Z]+)\]/');
		
		$texts = array();
		foreach ($variables as $key => $value) {
			if( ($value['replacement'] == 'location' || $value['replacement'] == 'locationLocative') && !$this->existsParameter($value['replacement']) ) {
				$replacement = $this->replacements['primary'.ucfirst($value['replacement'])];
			} else {
				$replacement = $this->replacements[$value['replacement']];
			}

			$phrase = $this->getParameter($replacement[0])->{$replacement[1]};
			$phrase = $this->phraseDecoratorFactory->create($phrase);
			$translation = $phrase->getTranslation($this->getParameter('language'), TRUE);

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