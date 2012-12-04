<?php 
namespace FrontModule\Components\Footer;

use Nette\Application\UI\Control;

class FooterControl extends \BaseModule\Components\BaseControl {

	public $userSiteOwnerReviewRepositoryAccessor;
	public $rentalRepositoryAccessor;
	public $location;

	public function inject(\Nette\DI\Container $dic) {
		$this->userSiteOwnerReviewRepositoryAccessor = $dic->userSiteOwnerReviewRepositoryAccessor;
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
		
		$testimonials = $this->userSiteOwnerReviewRepositoryAccessor->get()->findBy(array('location' => $this->location), array('id' => 'DESC'), 2);
		$template->testimonials = $testimonials;

		$lastRegisteredObjects = $this->rentalRepositoryAccessor->get()->findBy(array('location' => $this->location), array('id' => 'DESC'), 5);
		$template->lastRegisteredObjects = $lastRegisteredObjects

		$template->render();
	}



}