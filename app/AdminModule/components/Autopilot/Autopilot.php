<?php

namespace AdminModule\Components;

use BaseModule\Components\BaseControl,
	Nette\ArrayHash,
	Tra\Utils\Arrays;

class Autopilot extends BaseControl {

	protected $autopilot;

	public function render() {
		$template = $this->template;
		$template->setFile(dirname(__FILE__) . '/template.latte');
		
		$template->render();
	}

}