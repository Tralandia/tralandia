<?php
namespace FrontModule\Components\RootCountries;

use Environment\Environment;

class RootCountriesControl extends \BaseModule\Components\BaseControl {

	/**
	 * @var \Environment\Environment
	 */
	protected $environment;

	/** 
	 * @var Extras\Translator 
	 */
	protected $translator;

	/**
	 * @var \Entity\Location\Location
	 */
	public $locationRepositoryAccessor;
	
	public function injectDic(\Nette\DI\Container $dic) {
		$this->locationRepositoryAccessor = $dic->locationRepositoryAccessor;
	}

	public function __construct(\Extras\Translator $translator, Environment $environment)
	{
		parent::__construct();
		$this->environment = $environment;
		$this->translator = $translator;
	}

	public function render()
	{
		$template = $this->template;
		$presenter = $this->getPresenter();

		$template->countries = $this->getCountries();

		$template->render();
	}

	private function getCountries()
	{
		return $this->locationRepositoryAccessor->get()->getCountriesOrdered(
			$this->translator, 
			$this->environment->getLocale()->getCollator()
		);
	}

}
