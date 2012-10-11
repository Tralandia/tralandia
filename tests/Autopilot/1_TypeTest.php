<?php

namespace Autopilot;

use PHPUnit_Framework_TestCase, Nette, Extras;

require_once __DIR__ . '/../bootstrap.php';

/**
 * @backupGlobals disabled
 */
class BaseTest extends PHPUnit_Framework_TestCase
{
	public $context;
	public $model;
	public $typeRepository;

	public $entityTechnicalName = 'totoSaMaloZmazat';

	protected function setUp() {
		$this->context = Nette\Environment::getContext();
		$this->model = $this->context->model;
		$this->typeRepository = $this->context->taskTypeRepository;
	}

	public function testCreate() {

		$type = new \Entity\Task\Type;

		$name = 'Toto sa malo zmazat';
		$type->name = $name;

		$type->technicalName = $this->entityTechnicalName;

		$mission = 'Toto treba spravit!';
		$type->mission = $mission;

		$durationPaid = 4.5;
		$type->durationPaid = $durationPaid;

		$validation = array();
		$type->validation = $validation;

		$timeLimit = 380;
		$type->timeLimit = $timeLimit;

		$actions = array();
		$type->actions = $actions;

		$this->model->persist($type);
		$this->model->flush();

		$this->assertSame($name, $type->name);
	}

	public function testDelete() {
		$type = $this->typeRepository->findOneBy(array('technicalName' => $this->entityTechnicalName));
		$this->assertInstanceOf('\Entity\Task\Type', $type);

		$this->model->remove($type);
		$this->model->flush();

		$type = $this->typeRepository->findOneBy(array('technicalName' => $this->entityTechnicalName));
		$this->assertNull($type);
	}

}