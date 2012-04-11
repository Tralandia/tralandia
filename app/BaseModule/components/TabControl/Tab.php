<?php
namespace BaseModule\Components\TabControl;

use Nette\Application\UI\Control;

class Tab extends Control {
	
	public $id;

	public $header;

	protected $content;

	protected $isControl = false;

	public $active = false;

	public function __construct(TabControl $parent, $id) {

		parent::__construct($parent, $id);
		$this->id = $id;
		
	}

	public function setContent($content) {
		if($content instanceof Control) {
			$this->isControl = true;
		}
		$this->content = $content;
	}

	public function getContent() {
		return $this->content;
	}

	public function isControl() {
		return $this->isControl;
	}

}