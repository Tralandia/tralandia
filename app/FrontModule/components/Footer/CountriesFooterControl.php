<?php 
namespace FrontModule\Components\Footer;

use Nette\Application\UI\Control;
use Service\Seo\ISeoServiceFactory;

class CountriesFooterControl extends \BaseModule\Components\BaseControl {

	protected $locationRepositoryAccessor;
	protected $locationTypeRepositoryAccessor;

	protected $seoFactory;

	public function __construct()
	{
		parent::__construct();
	}

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
		
		// Get USA, Australia, Canada
		$typeContinent = $this->locationTypeRepositoryAccessor->get()->findBySlug('continent');
		$continentLocation = array();
		// USA
		$continentLocation['us'] = $this->locationRepositoryAccessor->get()->findOneBy(array('slug' => 'usa', 'type' => $typeContinent));
		$continentName['us'] = $translator->translate($continentLocation['us']->name);

		// Canada
		$continentLocation['ca'] = $this->locationRepositoryAccessor->get()->findOneBy(array('slug' => 'canada', 'type' => $typeContinent));
		$continentName['ca'] = $translator->translate($continentLocation['ca']->name);

		// Australia
		$continentLocation['au'] = $this->locationRepositoryAccessor->get()->findOneBy(array('slug' => 'australia', 'type' => $typeContinent));
		$continentName['au'] = $translator->translate($continentLocation['au']->name);

		// Get countries
		$typeCountry = $this->locationTypeRepositoryAccessor->get()->findBySlug('country');
		$locationsTemp = $this->locationRepositoryAccessor->get()->findByType($typeCountry);
		$locations = array();

		foreach ($locationsTemp as $location) {
			if (strlen($location->iso) == 4) {
				$tempName = $continentName[substr($location->iso, 0, 2)].' - ';
			} else {
				$tempName = '';
			}
			$link = $this->presenter->link('//:Front:Home:default', array('primaryLocation' => $location));
			$t = $tempName . $translator->translate($location->name);
			$locations[$t] = $this->seoFactory->create($link, $this->presenter->getLastCreatedRequest());
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