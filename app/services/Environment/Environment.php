<?php

namespace Environment;

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
	protected $translator;

	/**
	 * @var Locale
	 */
	protected $locale;

	/**
	 * @param Nette\Application\Request[] $request
	 * @param \Extras\ITranslatorFactory $translatorFactory
	 */
	public function __construct(array $request, \Extras\ITranslatorFactory $translatorFactory)
	{
		$this->request = reset($request);
		$this->primaryLocation = $this->getRequestParameter('primaryLocation');
		$this->language = $this->getRequestParameter('language');
		$this->translator = $translatorFactory->create($this->getLanguage());
	}

	/**
	 * @return \Entity\Location\Location
	 */
	public function getPrimaryLocation()
	{
		return $this->primaryLocation;
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
		return $this->translator;
	}

	/**
	 * @return Locale
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
}