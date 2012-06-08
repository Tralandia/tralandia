<?php

namespace Service\Autopilot;

use Nette\Utils\Arrays;

class Autopilot extends \Nette\Object {

	public static function addTask($typeName, array $attributes = array(), array $params = array()) {
		$type = Type::getByTechnicalName($typeName);
		if(!$type instanceof \Extras\Models\Service) {
			throw new \Nette\InvalidArgumentException('Argument $typeName does not match with the expected value');
		}

		$task = Task::get();
		$task->type = $type;
		$task->name = $type->name;
		$task->mission = $type->mission;

		$links = array();
		if(isset($params['links'])) {
			$links = (array) $params['links'];
		}

		if($type->technicalName == 'improveRental') {
			$links = Arrays::get($attributes, 'links', array());
			if(array_key_exists('rental', $params) && ($params['rental'] instanceof \Service\Rental\Rental || $params['rental'] instanceof \Entity\Rental\Rental)) {
				$links['rental'] = $params['rental'];
			} else {
				throw new \Nette\InvalidArgumentException('Argument $params["rental"] does not match with the expected value');
			}
		} else if($type->technicalName == '\Location\Location - Level2HasNoParent') {
			$links = Arrays::get($attributes, 'links', array());
			if(array_key_exists('location', $params) && ($params['location'] instanceof \Service\Location\Location || $params['location'] instanceof \Entity\Location\Location)) {
				$links['location'] = array('destination' => 'LocationLocation:edit', 'arguments' => array('id' => $params['location']->id));
			} else {
				throw new \Nette\InvalidArgumentException('Argument $params["location"] does not match with the expected value');
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
		if(is_array($links) && count($links)) $task->links = $links;

		$task->save();
		return $task;
	}

	public static function getNextTask($user) {

		$qb = \Extras\Models\Service::getEm()->createQueryBuilder();
		$qb->select('*')
			->from(\Entity\User\User, 'u')
			->limit(10)
			->getQuery();
		debug($qb);

		return $task;

	}

}