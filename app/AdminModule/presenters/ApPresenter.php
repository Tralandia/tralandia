<?php

namespace AdminModule;

use Nette\ArrayHash;

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

		$links = new ArrayHash;
		foreach ($this->task->links as $linkName => $link) {
			$links->{$linkName} = ArrayHash::from(array(
				'title' => $link['title'],
				'link' => $this->lazyLink($link['link']['destination'], $link['link']['arguments']),
			));
			$links->{$linkName}->link->setParameter('display', 'modal');
		}

		$this->task->executeActions('onDone');

		$this->template->task = $this->task;
		$this->template->links = $links;
		
	}

	public function createComponentAutopilot($name){
		$autopilot = new \AdminModule\Components\Autopilot($this, $name);
		$autopilot->task = $this->task;
		return $autopilot;
	}

}
