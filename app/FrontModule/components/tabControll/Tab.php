<?php
namespace FrontModule\TabControl;

use Nette\Application\UI\Control;

class Tab extends Control {
	
	public $id;

	public $header;

	public $content;

	public $active = false;

	public function __construct(TabControl $parent, $id) {

		parent::__construct($parent, $id);
		$this->setId($id);
		
	}

	public function __call($name, $args) {

		$this->{lcfirst(substr($name, 3))} = $args[0];
		return $this;

	}

}