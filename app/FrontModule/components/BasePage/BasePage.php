<?php 
namespace FrontModule\Components\BasePage;

use Nette\Application\UI\Control;

abstract class BasePage extends \BaseModule\Components\BaseControl {

	public function render() {
		$template = $this->template;
		$template->setFile(dirname(__FILE__) . '/page.latte');
		$template->render();
	}

}