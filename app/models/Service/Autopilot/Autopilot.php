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

		//if(!$task->technicalName) $task->technicalName = $type->technicalName;
		if(is_array($links) && count($links)) $task->links = $links;

		$task->save();
		return $task;
	}

	/**
	 * Get the next relevant task for user
	 * @param  \Service\User\User 		$user
	 * @return \Service\Autopilot\Task
	 */
	public static function getNextTask($user = null) {

		if ($user instanceof \Security\User) {
			$user = \Service\User\User::get($user->getIdentity()->id);
		} else if (!$user instanceof \Entity\User\User) {
			throw new \Nette\Exception('Argument $user must be instance of \Entity\User\User');
		}

		$qb = \Extras\Models\Service::getEm()->createQueryBuilder();
		$qb->setMaxResults(1)
			->select('t')
			->from('\Entity\Autopilot\Task', 't')
			->leftJoin('t.usersExcluded', 'excluded', \Doctrine\ORM\Query\Expr\Join::WITH, 'excluded.id != :userId')
			->leftJoin('t.userRole', 'role')
			->leftJoin('t.userCountry', 'country')
			->leftJoin('t.userLanguage', 'language')
			->where('t.user = :userId');

		$i=0;
		foreach($user->combinations as $combination) {
			$qb->orWhere('(
					t.user IS NULL 
					AND role.id = :userRoleId 
					AND country.id IN (:userCountry'. $i .', :NULL)
					AND language.id IN (:userLanguage'. $i .', :NULL)
					AND (
						t.userLanguageLevel >= :userLanguageLevel'. $i .'
						OR t.userLanguageLevel IS NULL
					)
				)')
				->setParameter('userCountry'. $i, $combination->country->id)
				->setParameter('userLanguage'. $i, $combination->language->id)
				->setParameter('userLanguageLevel'. $i, $combination->country->id);
			$i++;
		}

		$qb->andwhere('t.startTime < :now')
			->setParameter('now', new \Extras\Types\DateTime)
			->setParameter('userId', $user->id)
			->setParameter('userRoleId', $user->role->id)
			->setParameter('NULL', NULL)
			->orderBy('t.due', 'ASC');

		return $qb->getQuery()->getSingleResult();

	}

	/**
	 * @param  \Entity\Autopilot\Task 	$task
	 * @param  string 					$recurrenceDelay
	 * @return \Entity\Autopilot\Task
	 */
	public static function createRecurrenceTask($task, $recurrenceDelay = NULL) {

		if (!$task instanceof \Entity\Autopilot\Task) {
			throw new ServiceException('Argument $task must be instance of \Entity\Autopilot\Task');
		}

		if (!$recurrenceDelay) {
			if ($task->recurrenceData) {
				$recurrenceDelay = $task->recurrenceData;
			} else {
				return false;
			}
		}

		$newTask = \Service\Autopilot\Task::get();
		$newTask->setStartTime(
			$task->startTime->modify($recurrenceDelay)
		);

		$newTask->type = $task->type;
		$newTask->subtype = $task->subtype;
		$newTask->name = $task->name;
		$newTask->mission = $task->mission;
		$newTask->due = $task->due;
		$newTask->durationPaid = $task->durationPaid;
		$newTask->links = $task->links;
		$newTask->reservedFor = $task->reservedFor;
		$newTask->user = $task->user;
		$newTask->userCountry = $task->userCountry;
		$newTask->userLanguage = $task->userLanguage;
		$newTask->userLanguageLevel = $task->userLanguageLevel;
		$newTask->userRole = $task->userRole;

		foreach ($task->usersExcluded as $key => $user) {
			$newTask->addUsersExcluded($user);
		}

		$newTask->validation = $task->validation;
		$newTask->actions = $task->actions;
		$newTask->recurrenceData = $task->recurrenceData;
		
		$newTask->save();

		return $newTask;

	}

	/**
	 * Set task done and move it from \Service\Autopilot\Task to \Service\Autopilot\TaskArchived
	 * @param  \Entity\Autopilot\Task $task
	 * @return \Entity\Autopilot\TaskArchived
	 */
	public static function setTaskDone($task) {

		if (!$task instanceof \Entity\Autopilot\Task) {
			throw new ServiceException('Argument $task must be instance of \Entity\Autopilot\Task');
		}
		\Service\Autopilot\Task::get($task)->executeActions('onDone');

		// create recurrence task if current task has recurrence data
		self::createRecurrenceTask($task);

		$taskArchived = \Service\Autopilot\TaskArchived::get();
		$taskArchived->type				= $task->type;
		$taskArchived->subtype			= $task->subtype;
		$taskArchived->name				= $task->name;
		$taskArchived->mission			= $task->mission;
		$taskArchived->startTime		= $task->startTime;
		$taskArchived->due				= $task->due;
		$taskArchived->durationPaid		= $task->durationPaid;
		$taskArchived->links			= $task->links;
		$taskArchived->user				= $task->user;
		$taskArchived->userCountry		= $task->userCountry;
		$taskArchived->userLanguage		= $task->userLanguage;
		$taskArchived->userLanguageLevel = $task->userLanguageLevel;
		$taskArchived->userRole			= $task->userRole;

		foreach ($task->usersExcluded as $user) {
			$taskArchived->addUsersExcluded($user);
		}

		$taskArchived->validation		= $task->validation;
		$taskArchived->actions			= $task->actions;
		$taskArchived->completed		= new \Nette\DateTime;
		$taskArchived->save();

		// delete old task
		\Service\Autopilot\Task::get($task)->delete();

		return $taskArchived;

	}

	/**
	 * Move task from \Service\Autopilot\TaskArchived to \Service\Autopilot\Task
	 * @param \Entity\Autopilot\TaskArchived $taskArchived
	 * @return \Entity\Autopilot\Task
	 */
	public static function setTaskNotDone($taskArchived) {
debug($taskArchived);
		if (!$taskArchived instanceof \Entity\Autopilot\TaskArchived) {
			throw new ServiceException('Argument $taskArchived must be instance of \Entity\Autopilot\TaskArchived');
		}
		$task->executeActions('onNotDone');

		$task = \Service\Autopilot\Task::get();
		$task->type				= $taskArchived->type;
		$task->subtype			= $taskArchived->subtype;
		$task->name				= $taskArchived->name;
		$task->mission			= $taskArchived->mission;
		$task->startTime		= $taskArchived->startTime;
		$task->due				= $taskArchived->due;
		$task->durationPaid		= $taskArchived->durationPaid;
		$task->links			= $taskArchived->links;
		$task->user				= $taskArchived->user;
		$task->userCountry		= $taskArchived->userCountry;
		$task->userLanguage		= $taskArchived->userLanguage;
		$task->userLanguageLevel= $taskArchived->userLanguageLevel;
		$task->userRole			= $taskArchived->userRole;
		$task->usersExcluded	= $taskArchived->usersExcluded;
		$task->validation		= $taskArchived->validation;
		$task->actions			= $taskArchived->actions;
		$task->save();

		// delete old archived task
		\Service\Autopilot\TaskArchived::get($taskArchived)->delete();

		return $task;

	}

}