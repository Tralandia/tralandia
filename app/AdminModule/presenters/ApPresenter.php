<?php

namespace AdminModule;

use Nette\ArrayHash;

class ApPresenter extends BasePresenter {

	public $task;

	public function actionTask($id){
		$this->task = \Service\Autopilot\Task::get($id);

		if(!$this->task) {
			$typeName = '\Location\Location - Level2HasNoParent';
			$attributes = array();
			$params = array(
				'location' => \Service\Location\Location::get(3),
			);
			$this->task = \Service\Autopilot\Autopilot::addTask($typeName, $attributes, $params);
			// @todo method or operation is not implemented
			//throw new \Nette\NotImplementedException('Requested method or operation is not implemented');
		}

		$links = new ArrayHash;
		foreach ($this->task->links as $linkName => $link) {
			$links->{$linkName} = ArrayHash::from(array(
				'title' => isset($link['title']) ? $link['title'] : $linkName,
				'link' => $this->lazyLink($link['destination'], isset($link['arguments'])?($link['arguments']):(NULL)),
			));
			$links->{$linkName}->link->setParameter('display', 'modal');
		}

		$this->task->done();

		$this->template->task = $this->task;
		$this->template->links = $links;

		$roles = \Service\User\RoleList::getByEmployee(TRUE);
		debug($roles->toArray());
		$users = \Service\User\UserList::getByRole($roles->toArray());

		$this->template->users = $users;

		// \Service\Autopilot\Autopilot::getNextTask();
		
	}

	public function createComponentAutopilot($name){
		$autopilot = new \AdminModule\Components\Autopilot($this, $name);
		$autopilot->task = $this->task;
		return $autopilot;
	}

}
