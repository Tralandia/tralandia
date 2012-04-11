<?php 
namespace FrontModule\Breadcrumb;

use Nette\Application\UI\Control;

class Breadcrumb extends Control {

	public function render() {
		$template = $this->template;
		$template->setFile(dirname(__FILE__) . '/template.latte');
		$template->setTranslator($this->presenter->getService('translator'));
		$template->render();
	}

}