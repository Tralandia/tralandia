<?php

namespace Service\Autopilot;

use Nette\Utils\Arrays;

class Autopilot extends \Nette\Object {

	public $taskRepository;
	public $taskTypeRepository;
	public $taskServiceFactory;

	public function createTask($typeName) {
		$type = $this->taskTypeRepository->findOneBy(array('technicalName' => $typeName));
		if(!$type instanceof \Entity\Task\Type) {
			throw new \Nette\InvalidArgumentException("Type tasku '$typeName' neexistuje!");
		}

		$taskService = $this->taskServiceFactory->create();
		$task = $taskService->getEntity();
		$task->type = $type;
		$task->name = $type->name;
		$task->mission = $type->mission;
		
		$task->due = new \Nette\DateTime;
		$task->due->modify("+{$type->timeLimit} sec");

		return $taskService;
	}

	// /**
	//  * Get the next relevant task for user
	//  * @param  \Entity\User\User|\Security\User $user
	//  * @return \Service\Autopilot\Task
	//  */
	// public static function getNextTask($user = null) {

	// 	if ($user instanceof \Security\User) {
	// 		$user = \Service\User\User::get($user->getIdentity()->id);
	// 	} else if (!$user instanceof \Entity\User\User) {
	// 		throw new \Exception('Argument $user must be instance of \Entity\User\User');
	// 	}

	// 	$qb = \Extras\Models\Service::getEm()->createQueryBuilder();
	// 	$qb->setMaxResults(1)
	// 		->select('t')
	// 		->from('\Entity\Autopilot\Task', 't')
	// 		->leftJoin('t.usersExcluded', 'excluded', \Doctrine\ORM\Query\Expr\Join::WITH, 'excluded.id != :userId')
	// 		->leftJoin('t.userRole', 'role')
	// 		->leftJoin('t.userCountry', 'country')
	// 		->leftJoin('t.userLanguage', 'language')
	// 		->where('t.user = :userId');

	// 	$i=0;
	// 	if (count($user->combinations)) {
	// 		foreach($user->combinations as $combination) {
	// 			$qb->orWhere('(
	// 					t.user IS NULL 
	// 					AND role.id = :userRoleId 
	// 					AND country.id IN (:userCountry'. $i .', :NULL)
	// 					AND language.id IN (:userLanguage'. $i .', :NULL)
	// 					AND (
	// 						t.userLanguageLevel >= :userLanguageLevel'. $i .'
	// 						OR t.userLanguageLevel IS NULL
	// 					)
	// 				)')
	// 				->setParameter('userCountry'. $i, $combination->country->id)
	// 				->setParameter('userLanguage'. $i, $combination->language->id)
	// 				->setParameter('userLanguageLevel'. $i, $combination->country->id);
	// 			$i++;
	// 		}
	// 		$qb->setParameter('NULL', NULL)
	// 			->setParameter('userRoleId', $user->role->id);
	// 	}

	// 	$qb->andwhere('t.startTime < :now')
	// 		->setParameter('now', new \Extras\Types\DateTime)
	// 		->setParameter('userId', $user->id)
	// 		->orderBy('t.due', 'ASC');

	// 	$task = $qb->getQuery()->getSingleResult();

	// 	if ($task) {
	// 		$task = \Service\Autopilot\Task::get($task);
	// 		$task->reserve($user->getEntity());
	// 		$task->save();
	// 	}

	// 	return $task;

	// }

	// /**
	//  * Get all stackable tasks to given $task
	//  * @param  	\Entity\Autopilot\Task 				&$task
	//  * @param  	\Entity\User\User|\Security\User 	$user
	//  * @return 	array|NULL							tasks id's
	//  */
	// public static function getStackableTasks(&$task, $user) {

	// 	if (!$task instanceof \Entity\Autopilot\Task) {
	// 		throw new \Exception('Argument $task must be instance of \Entity\Autopilot\Task');
	// 	}
	// 	if ($user instanceof \Security\User) {
	// 		$user = \Service\User\User::get($user->getIdentity()->id);
	// 	}

	// 	if (!$task->type->stackable) { // If the task type is't stackable, return null
	// 		return NULL;
	// 	}

	// 	$qb = \Extras\Models\Service::getEm()->createQueryBuilder();
	// 	$qb->select('t.id')
	// 		->from('\Entity\Autopilot\Task', 't')
	// 		->leftJoin('t.usersExcluded', 'excluded', \Doctrine\ORM\Query\Expr\Join::WITH, 'excluded.id != :userId')
	// 		->where('t.type = :taskType')
	// 		->andWhere('t.id != :stackerId')
	// 		->setParameter('stackerId', $task->id)
	// 		->setParameter('userId', $user->id)
	// 		->setParameter('taskType', $task->type->id)
	// 		->setMaxResults($task->type->stackable)
	// 		->orderBy('t.due', 'ASC');

	// 	$stack = $qb->getQuery()->getResult();

	// 	if ($task->links) {
	// 		foreach ($task->links as $linkName => $link) {
	// 			$ids = array();
	// 			foreach ($stack as $t) $ids[] = $t->id;

	// 			$task->links[$linkName]['arguments']['stack'] = $ids;
	// 			break;
	// 		}
	// 	}

	// 	return $stack;

	// }

	// /**
	//  * @param  \Entity\Autopilot\Task 	$task
	//  * @param  string 					$recurrenceDelay
	//  * @return \Entity\Autopilot\Task
	//  */
	// public static function createRecurrenceTask($task, $recurrenceDelay = NULL) {

	// 	if (!$task instanceof \Entity\Autopilot\Task) {
	// 		throw new \Exception('Argument $task must be instance of \Entity\Autopilot\Task');
	// 	}

	// 	if (!$recurrenceDelay) {
	// 		if ($task->recurrenceData) {
	// 			$recurrenceDelay = $task->recurrenceData;
	// 		} else {
	// 			return false;
	// 		}
	// 	}

	// 	$newTask = \Service\Autopilot\Task::get();
	// 	$newTask->setStartTime(
	// 		$task->startTime->modify($recurrenceDelay)
	// 	);

	// 	$newTask->type 				= $task->type;
	// 	$newTask->name 				= $task->name;
	// 	$newTask->technicalName		= $task->technicalName;
	// 	$newTask->entityName		= $task->entityName;
	// 	$newTask->entityId			= $task->entityId;
	// 	$newTask->mission 			= $task->mission;
	// 	$newTask->due 				= $task->due;
	// 	$newTask->durationPaid 		= $task->durationPaid;
	// 	$newTask->links 			= $task->links;
	// 	$newTask->reservedFor 		= $task->reservedFor;
	// 	$newTask->user 				= $task->user;
	// 	$newTask->userCountry 		= $task->userCountry;
	// 	$newTask->userLanguage 		= $task->userLanguage;
	// 	$newTask->userLanguageLevel = $task->userLanguageLevel;
	// 	$newTask->userRole 			= $task->userRole;
	// 	$newTask->validation 		= $task->validation;
	// 	$newTask->actions 			= $task->actions;
	// 	$newTask->recurrenceData 	= $task->recurrenceData;
	// 	// add excluded users
	// 	foreach ($task->usersExcluded as $key => $user) $newTask->addUsersExcluded($user);

	// 	$newTask->save();

	// 	return $newTask;

	// }

	// /**
	//  * Set task done and move it from \Service\Autopilot\Task to \Service\Autopilot\TaskArchived
	//  * @param  \Entity\Autopilot\Task $task
	//  * @return \Entity\Autopilot\TaskArchived
	//  */
	// public static function setTaskDone($task) {

	// 	if (!$task instanceof \Entity\Autopilot\Task) {
	// 		throw new \Exception('Argument $task must be instance of \Entity\Autopilot\Task');
	// 	}
	// 	\Service\Autopilot\Task::get($task)->executeActions('onDone');

	// 	// create recurrence task if current task has recurrence data
	// 	self::createRecurrenceTask($task);

	// 	$taskArchived = \Service\Autopilot\TaskArchived::get();
	// 	$taskArchived->type				= $task->type;
	// 	$taskArchived->name				= $task->name;
	// 	$taskArchived->technicalName	= $task->technicalName;
	// 	$taskArchived->entityName		= $task->entityName;
	// 	$taskArchived->entityId			= $task->entityId;
	// 	$taskArchived->mission			= $task->mission;
	// 	$taskArchived->startTime		= $task->startTime;
	// 	$taskArchived->due				= $task->due;
	// 	$taskArchived->durationPaid		= $task->durationPaid;
	// 	$taskArchived->links			= $task->links;
	// 	$taskArchived->user				= $task->user;
	// 	$taskArchived->userCountry		= $task->userCountry;
	// 	$taskArchived->userLanguage		= $task->userLanguage;
	// 	$taskArchived->userLanguageLevel= $task->userLanguageLevel;
	// 	$taskArchived->userRole			= $task->userRole;
	// 	$taskArchived->validation		= $task->validation;
	// 	$taskArchived->actions			= $task->actions;
	// 	$taskArchived->completed		= new \Nette\DateTime;
	// 	// add excluded users
	// 	foreach ($task->usersExcluded as $user) $taskArchived->addUsersExcluded($user);

	// 	$taskArchived->save();

	// 	// delete old task
	// 	\Service\Autopilot\Task::get($task)->delete();

	// 	return $taskArchived;

	// }

	// /**
	//  * Move task from \Service\Autopilot\TaskArchived to \Service\Autopilot\Task
	//  * @param \Entity\Autopilot\TaskArchived $taskArchived
	//  * @return \Entity\Autopilot\Task
	//  */
	// public static function setTaskNotDone($taskArchived) {

	// 	if (!$taskArchived instanceof \Entity\Autopilot\TaskArchived) {
	// 		throw new ServiceException('Argument $taskArchived must be instance of \Entity\Autopilot\TaskArchived');
	// 	}

	// 	$task = \Service\Autopilot\Task::get();
	// 	$task->type				= $taskArchived->type;
	// 	$task->name				= $taskArchived->name;
	// 	$task->technicalName	= $taskArchived->technicalName;
	// 	$task->entityName		= $taskArchived->entityName;
	// 	$task->entityId			= $taskArchived->entityId;
	// 	$task->mission			= $taskArchived->mission;
	// 	$task->startTime		= $taskArchived->startTime;
	// 	$task->due				= $taskArchived->due;
	// 	$task->durationPaid		= $taskArchived->durationPaid;
	// 	$task->links			= $taskArchived->links;
	// 	$task->user				= $taskArchived->user;
	// 	$task->userCountry		= $taskArchived->userCountry;
	// 	$task->userLanguage		= $taskArchived->userLanguage;
	// 	$task->userLanguageLevel= $taskArchived->userLanguageLevel;
	// 	$task->userRole			= $taskArchived->userRole;
	// 	$task->validation		= $taskArchived->validation;
	// 	$task->actions			= $taskArchived->actions;
	// 	// add excluded users
	// 	foreach ($task->usersExcluded as $user) $task->addUsersExcluded($user);
	// 	$task->save();
		
	// 	// delete old archived task
	// 	\Service\Autopilot\TaskArchived::get($taskArchived)->delete();

	// 	return $task;

	// }

}