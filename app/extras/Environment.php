<?php

namespace Extras;

use Nette;

class Environment extends Nette\Object {

	protected $location;
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
		return $this->locationRepositoryAccessor->get()->find($this->getRequestParameter('primaryLocation'));
	}

	public function getLanguage()
	{
		return $this->languageRepositoryAccessor->get()->find($this->getRequestParameter('language'));
	}

	public function getRequest()
	{
		return $this->request;
	}
	
	protected function getRequestParameter($name)
	{
		$parameters = $this->request->getParameters();
		return $parameters[$name];
	}
}