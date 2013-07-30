<?php
namespace FrontModule\Components\Footer;

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

//		$testimonials = $this->userSiteReviewRepositoryAccessor->get()->findBy(array('primaryLocation' => $this->location), array('id' => 'DESC'), 2);
//		$template->testimonials = $testimonials;

		$template->render();
	}

}

interface IFooterControlFactory {
	/**
	 * @param \Entity\Location\Location $location
	 *
	 * @return FooterControl
	 */
	public function create(\Entity\Location\Location $location);
}
