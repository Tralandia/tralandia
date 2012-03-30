<?php

namespace FrontModule;

abstract class BasePresenter extends \BasePresenter {
	
	protected function startup() {
		$this->autoCanonicalize = FALSE;
		parent::startup();
	}

}
