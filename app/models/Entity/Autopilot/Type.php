<?php

namespace Entity\Autopilot;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="autopilot_type")
 */
class Type extends \Entity\BaseEntityDetails {

	const ACTION_ON_SAVE = 'onSave';
	const ACTION_ON_DELEGATE = 'onDelegate';
	const ACTION_ON_COMPLETED = 'onCompleted';
	const ACTION_ON_DEFER = 'onDefer';

	/**
	 * @var string
	 * @ORM\Column(type="string")
	 */
	protected $name;

	/**
	 * @var string
	 * @ORM\Column(type="string")
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
	 * defined in minutes
	 */
	protected $durationPaid;

	/**
	 * @var json
	 * @ORM\Column(type="json", nullable=true)
	 */
	protected $validation;

	/**
	 * @var integer
	 * @ORM\Column(type="integer", nullable=true)
	 */
	protected $stackable;

	/**
	 * @var float
	 * @ORM\Column(type="float")
	 * defined in minutes
	 */
	protected $timeLimit = 2;

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
	 * @return \Entity\Autopilot\Type
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
	 * @return \Entity\Autopilot\Type
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
	 * @return \Entity\Autopilot\Type
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
	 * @return \Entity\Autopilot\Type
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
	 * @return \Entity\Autopilot\Type
	 */
	public function setValidation($validation) {
		$this->validation = $validation;

		return $this;
	}
		
	/**
	 * @return \Entity\Autopilot\Type
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
	 * @return \Entity\Autopilot\Type
	 */
	public function setStackable($stackable) {
		$this->stackable = $stackable;

		return $this;
	}
		
	/**
	 * @return \Entity\Autopilot\Type
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
	 * @return \Entity\Autopilot\Type
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
	 * @return \Entity\Autopilot\Type
	 */
	public function setActions($actions) {
		$this->actions = $actions;

		return $this;
	}
		
	/**
	 * @return \Entity\Autopilot\Type
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