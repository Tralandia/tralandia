<?php 
namespace FrontModule;

use Nette\Application\UI\Control;

class CountryMap extends Control {

	public function render() {
	    $template = $this->template;
	    $template->setFile(dirname(__FILE__) . '/sk.map.latte');
	    $template->setTranslator($this->presenter->getService('translator'));
	    $template->render();
	}

}