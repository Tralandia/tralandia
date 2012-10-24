<?php
namespace BaseModule\Components\TabControl;

use Nette\Application\UI\Control;

class Tab extends Control {
	
	public $id;

	public $header;

	protected $content;

	protected $isControl = false;

	public $active = false;

	public function __construct($id) {
		$this->id = $id;

		parent::__construct();
		
	}

	public function setHeading($header) {
		$this->header = $header;
		return $this;
	}

	public function setActive() {
		$this->active = true;
		return $this;
	}

	public function setContent($content) {
		if($content instanceof Control) {
			$this->isControl = true;
		}
		$this->content = $content;
		return $this;
	}

	public function getContent() {
		return $this->content;
	}

	public function isControl() {
		return $this->isControl;
	}

}