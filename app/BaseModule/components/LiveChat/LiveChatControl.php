<?php
namespace BaseModule\Components;


class LiveChatControl extends \BaseModule\Components\BaseControl {

	public function __construct() {
		parent::__construct();
	}


	public function render()
	{
		$this->template->show = true;

		$this->template->render();
	}


}

