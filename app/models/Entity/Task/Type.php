<?php

namespace Entity\Task;

use Doctrine\ORM\Mapping as ORM;
use	Extras\Annotation as EA;


/**
 * @ORM\Entity(repositoryClass="Repository\Task\TypeRepository")
 * @ORM\Table(name="task_type")
 * @EA\Service(name="\Service\Task\Type")
 * @EA\ServiceList(name="\Service\Task\TypeList")
 * @EA\Primary(key="id", value="name")
 */
class Type extends \Entity\BaseEntityDetails {

	const ON_SAVE = 'onSave';
	const ON_DELEGATE = 'onDelegate';
	const ON_COMPLETED = 'onCompleted';
	const ON_DEFER = 'onDefer';

	/**
	 * @var string
	 * @ORM\Column(type="string")
	 */
	protected $name;

	/**
	 * @var string
	 * @ORM\Column(type="string", unique=true, nullable=false)
	 * This is for internal use and quick
	 */
	protected $technicalName;

	/**
	 * @var text
	 * @ORM\Column(type="text")
	 */
	protected $mission;

	/**
	 * @var float
	 * @ORM\Column(type="float")
	 * defined in sec
	 */
	protected $durationPaid;

	/**
	 * @var json
	 * @ORM\Column(type="json", nullable=true)
	 */
	protected $validation;

	/**
	 * @var float
	 * @ORM\Column(type="float")
	 * defined in sec
	 */
	protected $timeLimit = 120;

	/**
	 * @var json
	 * @ORM\Column(type="json", nullable=true)
	 */
	protected $actions;


//@entity-generator-code <--- NEMAZAT !!!

	/* ----------------------------- Methods ----------------------------- */		
	public function __construct() {
		parent::__construct();
	}
		
	/**
	 * @param string
	 * @return \Entity\Task\Type
	 */
	public function setName($name) {
		$this->name = $name;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getName() {
		return $this->name;
	}
		
	/**
	 * @param string
	 * @return \Entity\Task\Type
	 */
	public function setTechnicalName($technicalName) {
		$this->technicalName = $technicalName;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getTechnicalName() {
		return $this->technicalName;
	}
		
	/**
	 * @param string
	 * @return \Entity\Task\Type
	 */
	public function setMission($mission) {
		$this->mission = $mission;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getMission() {
		return $this->mission;
	}
		
	/**
	 * @param float
	 * @return \Entity\Task\Type
	 */
	public function setDurationPaid($durationPaid) {
		$this->durationPaid = $durationPaid;

		return $this;
	}
		
	/**
	 * @return float|NULL
	 */
	public function getDurationPaid() {
		return $this->durationPaid;
	}
		
	/**
	 * @param json
	 * @return \Entity\Task\Type
	 */
	public function setValidation($validation) {
		$this->validation = $validation;

		return $this;
	}
		
	/**
	 * @return \Entity\Task\Type
	 */
	public function unsetValidation() {
		$this->validation = NULL;

		return $this;
	}
		
	/**
	 * @return json|NULL
	 */
	public function getValidation() {
		return $this->validation;
	}
		
	/**
	 * @param integer
	 * @return \Entity\Task\Type
	 */
	public function setStackable($stackable) {
		$this->stackable = $stackable;

		return $this;
	}
		
	/**
	 * @return \Entity\Task\Type
	 */
	public function unsetStackable() {
		$this->stackable = NULL;

		return $this;
	}
		
	/**
	 * @return integer|NULL
	 */
	public function getStackable() {
		return $this->stackable;
	}
		
	/**
	 * @param float
	 * @return \Entity\Task\Type
	 */
	public function setTimeLimit($timeLimit) {
		$this->timeLimit = $timeLimit;

		return $this;
	}
		
	/**
	 * @return float|NULL
	 */
	public function getTimeLimit() {
		return $this->timeLimit;
	}
		
	/**
	 * @param json
	 * @return \Entity\Task\Type
	 */
	public function setActions($actions) {
		$this->actions = $actions;

		return $this;
	}
		
	/**
	 * @return \Entity\Task\Type
	 */
	public function unsetActions() {
		$this->actions = NULL;

		return $this;
	}
		
	/**
	 * @return json|NULL
	 */
	public function getActions() {
		return $this->actions;
	}
}