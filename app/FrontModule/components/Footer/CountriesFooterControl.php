<?php 
namespace FrontModule\Components\Footer;

use Nette\Application\UI\Control;
use Service\Seo\ISeoServiceFactory;

class CountriesFooterControl extends \BaseModule\Components\BaseControl {

	protected $locationRepositoryAccessor;
	protected $locationTypeRepositoryAccessor;

	protected $seoFactory;

	public function injectSeo(ISeoServiceFactory $seoFactory)
	{
		$this->seoFactory = $seoFactory;
	}

	public function inject(\Nette\DI\Container $dic) {
		$this->locationRepositoryAccessor = $dic->locationRepositoryAccessor;
		$this->locationTypeRepositoryAccessor = $dic->locationTypeRepositoryAccessor;
	}

	public function render() {

		$template = $this->template;
		$template->setFile(dirname(__FILE__) . '/countriesFooterControl.latte');
		$translator = $this->presenter->getService('translator');
		$template->setTranslator($translator);
		
		$typeCountry = $this->locationTypeRepositoryAccessor->get()->findBySlug('country');
		$locationsTemp = $this->locationRepositoryAccessor->get()->findByType($typeCountry);
		$locations = array();

		foreach ($locationsTemp as $location) {
			$link = $this->presenter->link('//:Front:Home:default', array('primaryLocation' => $location));
			$locations[$translator->translate($location->name)] = $this->seoFactory->create($link, $this->presenter->getLastCreatedRequest());
		}

		$coll = new \Extras\Collator('sk_SK');
		$coll->ksort($locations);
		// $a = array_keys($locations);
		// usort($a, 'strcoll');

		$locations = array_chunk($locations, ceil(count($locations) / 6));
		
		//d($locations); #@debug
		// $this->presenter->terminate();
		$template->locations = $locations;

		$template->render();
	}



}