<?php

namespace AdminModule;


class ApPresenter extends BasePresenter {

	public $task;

	public function actionTask($id){
		$this->task = \Service\Autopilot\Task::get($id);
		// $typeName = 'test-1';
		// $attributes = array();
		// $params = array();
		// $task = \Service\Autopilot\Autopilot::addTask($typeName, $attributes, $params);
		// debug($task->getMainEntity());

		if(!$this->task) {
			// @todo method or operation is not implemented
			throw new \Nette\NotImplementedException('Requested method or operation is not implemented');
		}
		
	}

	public function createComponentAutopilot($name){
		$autopilot = new \AdminModule\Components\Autopilot($this, $name);
		$autopilot->task = $this->task;
		return $autopilot;
	}

}
