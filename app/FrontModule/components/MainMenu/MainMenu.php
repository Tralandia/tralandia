<?php 
namespace FrontModule\Components\MainMenu;

use Nette\Application\UI\Control;

class MainMenu extends \BaseModule\Components\BaseControl
 {

	public function render() {
		$template = $this->template;
		$template->setFile(dirname(__FILE__) . '/template.latte');
		$template->setTranslator($this->presenter->getService('translator'));
		$template->render();
	}

}