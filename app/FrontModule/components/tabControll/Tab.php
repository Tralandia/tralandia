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
		$this->id = $id;
		
	}

}