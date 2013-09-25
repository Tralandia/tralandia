<?php

namespace Service\Seo;

use Nette, Extras, Service, Doctrine, Entity;
use Nette\Utils\Strings;
use Routers\FrontRoute;
use Tralandia\Localization\Translator;
use Tralandia\BaseDao;

/**
 * @author Dávid Ďurika
 */
class SeoService extends Nette\Object {

	protected $request;
	protected $requestParameters;

	/**
	 * @var \Nette\Localization\ITranslator
	 */
	protected $translator;
	protected $page = NULL;
	protected $url;
	protected $phraseDecoratorFactory;

	protected $pathSegmentParameters;

	protected $replacements = array(
		'primaryLocation' => array(
			'primaryLocation',
			'name',
		),
		'primaryLocationLocative' => array(
			'primaryLocation',
			'name',
			array(
				Translator::VARIATION_CASE => \Entity\Language::LOCATIVE,
			),
		),
		'location' => array(
			'location', // request parameter's name
			'name', // entity attribute
		),
		'locationLocative' => array(
			'location',
			'name',
			array(
				Translator::VARIATION_CASE => \Entity\Language::LOCATIVE,
			),
		),
		'rentalTypePlural' => array(
			'rentalType',
			'name',
			array(
				'plural' => 1,
			),
		),
		'rental' => array(
			'rental',
			'name',
		),
	);

	protected $defaultVariation = array(
		Translator::VARIATION_PLURAL => \Entity\Language::DEFAULT_PLURAL,
		Translator::VARIATION_GENDER => \Entity\Language::DEFAULT_GENDER,
		Translator::VARIATION_CASE => \Entity\Language::DEFAULT_CASE,
	);

	/**
	 * @var \Tralandia\BaseDao
	 */
	private $pageDao;


	/**
	 * @param string $url
	 * @param Nette\Application\Request $request
	 * @param \Tralandia\BaseDao $pageDao
	 * @param \Nette\Localization\ITranslator $translator
	 *
	 */
	public function __construct($url, Nette\Application\Request $request, BaseDao $pageDao,
								Nette\Localization\ITranslator $translator)
	{
		$this->url = $url;
		$this->pageDao = $pageDao;
		$this->request = $request;
		$this->requestParameters = $this->request->getParameters();
		$this->translator = $translator;

		$this->pathSegmentParameters = [
			FrontRoute::LOCATION => FrontRoute::$pathParametersMapper[FrontRoute::LOCATION],
			FrontRoute::RENTAL_TYPE => FrontRoute::$pathParametersMapper[FrontRoute::RENTAL_TYPE],
		];
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
		$page = $this->getPage();
		if(!$page) return '';

		$h1 = $this->compilePattern($page->h1Pattern);
		return Strings::firstUpper($h1);
	}

	/**
	 * Retrun text for Title
	 * @return string
	 */
	public function getTitle() {
		$page = $this->getPage();
		if(!$page) return '';

		$title = $this->compilePattern($page->titlePattern);
		return Strings::firstUpper($title);
	}

	/**
	 * Retrun text for anchor text
	 * @return string
	 */
	public function getAnchorText() {
		return $this->getH1();
	}

	/**
	 * @return \Entity\Page|bool|null
	 */
	public function getPage() {
		if ($this->page === NULL) {
			$destination = ':' . $this->request->getPresenterName() . ':' . $this->getParameter('action');
			if ($destination == ':Front:RentalList:default') {
				$hash = array();
				foreach ($this->pathSegmentParameters as $key => $value) {
					if ($this->existsParameter($value)) {
						$hash[] = '/'.$key;
					}
				}

				$hash = implode('', $hash);
			} else if($destination == ':Front:Rental:detail') {
				$hash = '/rental';
			} else {
				$hash = '';
			}
			$page = $this->pageDao->findOneBy(array('hash' => $hash, 'destination' => $destination));
			if(!$page) {
				$page = FALSE;
			}

			$this->page = $page;
		}
		return $this->page;
	}

	/**
	 * Return parameter by given name
	 * @param  string $name parameter name
	 * @return Entity\BaseEntity|string
	 */
	public function getParameter($name) {
		$alias = Nette\Utils\Arrays::get($this->pathSegmentParameters, $name, NULL);
		return $this->requestParameters[$alias ?: $name];
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

//		$patternTranslation = $pattern->getTranslationText($this->getParameter('language'), TRUE);
		$patternTranslation = $this->translator->translate($pattern);
		if (!$patternTranslation) {
			return NULL;
		}

		$variables = Strings::matchAll($patternTranslation, '/\[(?P<replacement>[a-zA-Z]+)\]/');

		$texts = array();
		foreach ($variables as $value) {
			if( ($value['replacement'] == 'location' || $value['replacement'] == 'locationLocative')
				&& !$this->existsParameter(FrontRoute::$pathParametersMapper[FrontRoute::LOCATION]) )
			{
				$replacement = $this->replacements['primary'.ucfirst($value['replacement'])];
			} else {
				$replacement = $this->replacements[$value['replacement']];
			}

			/** @var $phrase \Entity\Phrase\Phrase */
			$phrase = $this->getParameter($replacement[0])->{$replacement[1]};

			$textKey = '['.$value['replacement'].']';
			if (array_key_exists(2, $replacement) && is_array($replacement[2])) {
				$texts[$textKey] = $this->translator->translate($phrase, NULL, $replacement[2]);
			} else {
				$texts[$textKey] = $this->translator->translate($phrase);
			}


		}

		return str_replace(array_keys($texts), array_values($texts), $patternTranslation);

	}

}

interface ISeoServiceFactory {
	/**
	 * @param string $url
	 * @param \Nette\Application\Request $request
	 *
	 * @return SeoService
	 */
	function create($url, \Nette\Application\Request $request);
}
