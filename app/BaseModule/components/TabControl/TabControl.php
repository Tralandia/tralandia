<?php 
namespace BaseModule\Components\TabControl;

use Nette\Application\UI\Control;

class TabControl extends Control {

	private $tabs = array();

	public function __construct() {

		parent::__construct();

	}

	public function render() {

	    $template = $this->template;
	    $template->setFile(dirname(__FILE__) . '/tabs.latte');
	    $template->setTranslator($this->presenter->getService('translator'));

	    $template->tabs = $this->tabs;

	    $template->render();

	}

	public function addTab($id) {

		$t = new Tab($id);

		$this->tabs[$id] = $t;

		return $t;

	}


}