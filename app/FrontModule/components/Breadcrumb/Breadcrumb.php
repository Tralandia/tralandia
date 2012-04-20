<?php 
namespace FrontModule\Components\Breadcrumb;

use Nette\Application\UI\Control;

class Breadcrumb extends \BaseModule\Components\BaseControl {

	public function render() {
		$template = $this->template;
		$template->setFile(dirname(__FILE__) . '/template.latte');
		$template->setTranslator($this->presenter->getService('translator'));
		$template->render();
	}

}