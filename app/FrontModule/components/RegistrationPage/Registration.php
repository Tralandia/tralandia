<?php 
namespace FrontModule\Components\RegistrationPage;

use Nette\Application\UI\Control,
	FrontModule\Components\BasePage\BasePage;

class Registration extends \BaseModule\Components\BaseControl {

	public function render() {
		$template = $this->template;
		$template->setFile(dirname(__FILE__) . '/page.latte');
		$template->render();
	}

}