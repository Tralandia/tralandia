<?php

namespace AdminModule\Components;

use BaseModule\Components\BaseControl,
	Nette\ArrayHash,
	Tra\Utils\Arrays;

class Autopilot extends BaseControl {

	protected $autopilot;
	public $task;

	public function render() {
		$template = $this->template;
		$template->setFile(dirname(__FILE__) . '/template.latte');
		$template->task = $this->task;
		$template->render();
	}

}