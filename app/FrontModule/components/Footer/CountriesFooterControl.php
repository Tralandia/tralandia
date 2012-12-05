<?php 
namespace FrontModule\Components\Footer;

use Nette\Application\UI\Control;
use Service\Seo\ISeoServiceFactory;

class CountriesFooterControl extends \BaseModule\Components\BaseControl {

	protected $locationRepositoryAccessor;
	protected $locationTypeRepositoryAccessor;

	protected $seoFactory;

	public function inject(\Nette\DI\Container $dic) {
		$this->locationRepositoryAccessor = $dic->locationRepositoryAccessor;
		$this->locationTypeRepositoryAccessor = $dic->locationTypeRepositoryAccessor;
	}

	public function injectSeo(ISeoServiceFactory $seoFactory)
	{
		$this->seoFactory = $seoFactory;
	}

	public function render() {

		$template = $this->template;
		$template->setFile(dirname(__FILE__) . '/countriesFooterControl.latte');
		$template->setTranslator($this->presenter->getService('translator'));
		
		$typeCountry = $this->locationTypeRepositoryAccessor->get()->findBySlug('country');
		$locationsTemp = $this->locationRepositoryAccessor->get()->findByType($typeCountry);
		$locations = array();
		foreach ($locationsTemp as $location) {
			$link = $this->presenter->link('//:Front:Rental:list', array('primaryLocation' => $location));
			$locations[] = array(
				'entity' => $location,
				'link' => $link,
				'seo' => $this->seoFactory->create($link),
			);
		}
		$template->locations = $locations;

		$template->render();
	}



}