<?php

namespace AdminModule;

use Nette\Environment;

abstract class BasePresenter extends \BasePresenter {
	

	protected function startup() {
		$this->autoCanonicalize = FALSE;
		parent::startup();
	}
}
