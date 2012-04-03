<?php

namespace Services\Autopilot;


class Task extends \Services\BaseService {
	
	const MAIN_ENTITY_NAME = '\Entities\Autopilot\Task';

	// @todo addAutopilotTask
	public static function create(array $params) {
		$task = self::get();
		return $task;
	}
	
}
