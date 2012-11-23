<?php

namespace Extras;

use Nette;

class Environment extends Nette\Object {

	protected $prymaryLocation;
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


	public function getPrimaryLocation()
	{
		if(!$this->prymaryLocation) {
			$locationId = $this->getRequestParameter('primaryLocation');
			$this->primaryLocation = $this->locationRepositoryAccessor->get()->find($locationId);
		}
		return $this->primaryLocation;
	}

	public function getLanguage()
	{
		if(!$this->language) {
			$languageId = $this->getRequestParameter('language');
			$this->language = $this->languageRepositoryAccessor->get()->find($languageId);
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