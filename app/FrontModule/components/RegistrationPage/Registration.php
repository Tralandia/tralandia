<?php 
namespace FrontModule\Components\RegistrationPage;

use Nette\Application\UI\Control,
	FrontModule\Components\BasePage\BasePage;

class Registration extends BasePage {

	public function render() {
		$template = $this->template;
		$template->setFile(dirname(__FILE__) . '/page.latte');
		$template->setTranslator($this->presenter->getService('translator'));
		$template->render();
	}

}