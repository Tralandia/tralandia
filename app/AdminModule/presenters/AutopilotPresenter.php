<?php

namespace AdminModule;


class ApPresenter extends BasePresenter {

	public function actionTask($id){
		$this->task = \Service\Autopilot\Task::get($id);
		if(!$this->task->id) {
			// @todo method or operation is not implemented
			throw new \Nette\NotImplementedException('Requested method or operation is not implemented');
		}
		
	}

	public function createComponentAutopilot($name){
		$autopilot = new \AdminModule\Components\Autopilot($this, $name);
		return $autopilot;
	}

}
