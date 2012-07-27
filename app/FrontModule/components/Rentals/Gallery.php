<?php 
namespace FrontModule\Components\Rentals;

use Nette\Application\UI\Control,
	FrontModule\Components\BasePage\BasePage;

class Gallery extends \BaseModule\Components\BaseControl {

	private $media;

	public function __construct($parent, $name, $media) {
		$this->media = $media;
		parent::__construct($parent, $name, $media);
	}

	public function render() {

		$template = $this->template;
		$template->media = $this->media;
		$template->setFile(dirname(__FILE__) . '/gallery.latte');
		$template->render();

	}

}