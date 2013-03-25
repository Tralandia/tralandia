<?php

namespace Environment;

use Entity\Language;
use Entity\Location\Location;
use Nette;

class Environment extends Nette\Object {

	/**
	 * @var \Entity\Location\Location
	 */
	protected $primaryLocation;

	/**
	 * @var \Entity\Language
	 */
	protected $language;

	/**
	 * @var Nette\Application\Request
	 */
	protected $request;

	/**
	 * @var \Extras\ITranslatorFactory
	 */
	protected $translatorFactory;

	/**
	 * @var \Extras\Translator
	 */
	protected $translator;

	/**
	 * @var \Environment\Locale
	 */
	protected $locale;

	/**
	 * @param \Entity\Location\Location $primaryLocation
	 * @param \Entity\Language $language
	 * @param \Extras\ITranslatorFactory $translatorFactory
	 */
	public function __construct(Location $primaryLocation, Language $language, \Extras\ITranslatorFactory $translatorFactory)
	{
		$this->primaryLocation = $primaryLocation;
		$this->language = $language;
		$this->translatorFactory = $translatorFactory;
	}

	/**
	 * @return \Entity\Location\Location
	 */
	public function getPrimaryLocation()
	{
		return $this->primaryLocation;
	}


	/**
	 * @return \Entity\Currency|NULL
	 */
	public function getCurrency()
	{
		return $this->primaryLocation->getDefaultCurrency();
	}

	/**
	 * @return \Entity\Language
	 */
	public function getLanguage()
	{
		return $this->language;
	}

	/**
	 * @return \Extras\Translator
	 */
	public function getTranslator()
	{
		if(!$this->translator) {
			$this->translator = $this->translatorFactory->create($this->language);
		}
		return $this->translator;
	}

	/**
	 * @return \Environment\Locale
	 */
	public function getLocale()
	{
		if(!$this->locale) {
			$this->locale = new Locale($this);
		}
		return $this->locale;
	}

	/**
	 * @return \Nette\Application\Request
	 */
	protected function getRequest()
	{
		return $this->request;
	}

	/**
	 * @param $name
	 *
	 * @return mixed|null
	 */
	protected function getRequestParameter($name)
	{
		$parameters = $this->request->getParameters();
		return isset($parameters[$name]) ? $parameters[$name] : NULL;
	}


	/**
	 * @param Nette\Application\Request[] $request
	 * @param \Extras\ITranslatorFactory $translatorFactory
	 *
	 * @return \Environment\Environment
	 */
	public static function createFromRequest(array $request, \Extras\ITranslatorFactory $translatorFactory) {
		$request = reset($request);
		$parameters = $request->getParameters();
		$primaryLocation = $parameters['primaryLocation'];
		$language = $parameters['language'];
		return new self($primaryLocation, $language, $translatorFactory);
	}

}

interface IEnvironmentFactory {
	/**
	 * @param \Entity\Location\Location $location
	 * @param \Entity\Language $language
	 *
	 * @return Environment
	 */
	public function create(Location $location, Language $language);
}
