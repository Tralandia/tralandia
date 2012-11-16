<?php 
namespace FrontModule\Components\Rentals;

use Nette\Application\UI\Control,
	FrontModule\Components\BasePage\BasePage;

class TopRentals extends \BaseModule\Components\BaseControl {

	public $rentalRepository;

	public function __construct($rentalRepository) {

		$this->rentalRepository = $rentalRepository;

		parent::__construct();

	}

	public function render() {

		$topRentals = $this->rentalRepository->findBy(array(), null, 15);

		$template = $this->template;
		$template->setFile(dirname(__FILE__) . '/top.latte');
		$template->render();

	}

}