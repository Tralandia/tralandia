<?php 
namespace FrontModule\Components\Footer;

use Nette\Application\UI\Control;

class FooterControl extends \BaseModule\Components\BaseControl {

	public $userSiteReviewRepositoryAccessor;
	public $rentalRepositoryAccessor;
	public $location;

	public function inject(\Nette\DI\Container $dic) {
		$this->userSiteReviewRepositoryAccessor = $dic->userSiteReviewRepositoryAccessor;
		$this->rentalRepositoryAccessor = $dic->rentalRepositoryAccessor;
	}

	public function __construct(\Entity\Location\Location $location) {
		$this->location = $location;
		parent::__construct();
	}

	public function render() {

		$template = $this->template;
		$template->setFile(dirname(__FILE__) . '/template.latte');
		$template->setTranslator($this->presenter->getService('translator'));
		
		$testimonials = $this->userSiteReviewRepositoryAccessor->get()->findBy(array('primaryLocation' => $this->location), array('id' => 'DESC'), 2);
		$template->testimonials = $testimonials;

		$lastRegisteredObjects = $this->rentalRepositoryAccessor->get()->findBy(array('primaryLocation' => $this->location), array('id' => 'DESC'), 5);
		$template->lastRegisteredObjects = $lastRegisteredObjects;

		$template->render();
	}



}