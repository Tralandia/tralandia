<?php 
namespace FrontModule;

use Nette\Application\UI\Control;

class TabsBar extends Control {

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

	public function addTab(\Service\Dictionary\Phrase $phrase, $attrId, $content) {

		$this->tabs[$attrId] = array(
			'attrId' => $attrId,
			'phrase' => $phrase,
			'content' => $content
		);

	}

	public function setActive($id) {

		$this->active = $id;

	}


}