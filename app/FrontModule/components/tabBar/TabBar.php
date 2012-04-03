<?php 
namespace FrontModule;

use Nette\Application\UI\Control;

class TabBar extends Control {

	private $tabs = array();
	private $active = null;

	public function render() {

	    $template = $this->template;
	    $template->setFile(dirname(__FILE__) . '/tabs.latte');
	    $template->setTranslator($this->presenter->getService('translator'));

	    $template->tabs = $this->tabs;
	    $template->active = $this->active;

	    $template->render();

	}

	public function addTab(\Service\Dictionary\Phrase $name, $content) {

		$this->tabs[$name->id] = array(
			'name' => $name,
			'content' => $content
		);

	}

	public function setActive($id) {

		$this->active = $id;

	}


}