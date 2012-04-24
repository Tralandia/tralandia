<?php 
namespace FrontModule\Components\GenericPage;

use Nette\Application\UI\Control,
	FrontModule\Components\BasePage\BasePage;

class Generic extends \BaseModule\Components\BaseControl {

	public $slug;

	public function render() {


		$this->template->page = \Nette\ArrayHash::from(array(
			'heading' => 122,
			'content' => 5
		));
		// $this->template->page = \Service\Page\Generic::getBySlug($this->slug);

		$template = $this->template;
		$template->setFile(dirname(__FILE__) . '/page.latte');
		$template->render();

	}

}