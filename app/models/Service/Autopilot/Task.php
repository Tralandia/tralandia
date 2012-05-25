<?php

namespace Service\Autopilot;

class Task extends \Service\BaseService {
	
	const MAIN_ENTITY_NAME = '\Entity\Autopilot\Task';

	// @todo addAutopilotTask
	// public static function create(array $params) {
	// 	$task = self::get();
	// 	return $task;
	// }
	
	public function defer(\DateTime $date) {
		$this->executeActions('onDefer');
		$this->defer = $date;
		return $this;
	}

	public function reserve(\Entity\User\User $user) {
		$this->executeActions('onReserve');
		$this->user = $user;
		return $this;
	}

	public function delegate(\Entity\User\User $user) {
		$this->executeActions('onDelegate');
		$this->user = $user;
		return $this;
	}

	public function executeActions($actions) {
		if(!isset($this->actions[$actions]) || !is_array($this->actions[$actions])) return TRUE;

		foreach ($this->actions[$actions] as $key => $value) {
			call_user_func_array(array($this, $key), $value);
		}

		return $this;
	}
	

	// - - - - - - - - - ACTOINS - - - - - - - - - 

	public function testAction() {
		debug('Ja som testovacia akcia :)', func_get_args());
	}
}
