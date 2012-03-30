<?php

namespace Services\Autopilot;

use Nette\Utils\Arrays;

class Autopilot extends \Nette\Object {

	public static function addTask($typeName, array $attributes = array(), array $params = array()) {
		$type = TypeService::getByTechnicalName($typeName);
		if(!$type instanceof \Extras\Models\Service) {
			throw new \Nette\InvalidArgumentException('Argument $typeName does not match with the expected value');
		}

		$task = TaskService::get();
		$task->type = $type;
		$task->name = $type->name;
		$task->mission = $type->mission;

		if($type->technicalName == 'improveRental') {
			$links = Arrays::get($params, 'links', array());
			if(array_key_exists('rental', $attributes) && ($attributes['rental'] instanceof \Services\Rental\RentalService || $attributes['rental'] instanceof \Entities\Rental\Rental)) {
				$links['rental'] = $attributes['rental'];
			} else {
				throw new \Nette\InvalidArgumentException('Argument $attributes["rental"] does not match with the expected value');
			}
		} else {

		}
		if(!$task->startTime) $task->startTime = new \Nette\DateTime;
		if(!$task->due) {
			$task->due = new \Nette\DateTime;
			$task->due->modify("+{$type->timeLimit} min");
		}
		if(!$task->durationPaid) $task->durationPaid = $type->durationPaid;
		if(!$task->validation) $task->validation = $type->validation;
		if(!$task->actions) $task->actions = $type->actions;

		$task->save();
		return $task;
	}

}