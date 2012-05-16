<?php

namespace AdminModule;


class AutopilotPresenter extends BasePresenter {

	public function createComponentAutopilot($name){
		$navigation = new \AdminModule\Components\Autopilot($this, $name);
		return $navigation;
	}

}
