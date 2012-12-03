<?php 
namespace FrontModule\Components\Footer;

use Nette\Application\UI\Control;

class FooterControl extends \BaseModule\Components\BaseControl {

	public $userSiteOwnerReviewRepositoryAccessor;
	public $location;

	public function inject(\Nette\DI\Container $dic) {
		$this->userSiteOwnerReviewRepositoryAccessor = $dic->userSiteOwnerReviewRepositoryAccessor;
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

		$template->render();
	}



}