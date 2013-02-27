<?php

namespace Extras;

use Nette;

class Environment extends Nette\Object {

	protected $primaryLocation;
	protected $language;

	protected $request;
	protected $currency;
	protected $locale;

	public $languageRepositoryAccessor;
	public $locationRepositoryAccessor;

	public function __construct(array $request, $languageRepositoryAccessor, $locationRepositoryAccessor)
	{
		$this->request = reset($request);
		$this->languageRepositoryAccessor = $languageRepositoryAccessor;
		$this->locationRepositoryAccessor = $locationRepositoryAccessor;
	}

	/**
	 * @return \Entity\Location\Location
	 */
	public function getPrimaryLocation()
	{
		if(!$this->primaryLocation) {
			$this->primaryLocation = $this->getRequestParameter('primaryLocation');
		}
		return $this->primaryLocation;
	}

	/**
	 * @return \Entity\Language
	 */
	public function getLanguage()
	{
		if(!$this->language) {
			$this->language = $this->getRequestParameter('language');
		}
		return $this->language;
	}

	public function getRequest()
	{
		return $this->request;
	}
	
	protected function getRequestParameter($name)
	{
		$parameters = $this->request->getParameters();
		return isset($parameters[$name]) ? $parameters[$name] : NULL;
	}
}