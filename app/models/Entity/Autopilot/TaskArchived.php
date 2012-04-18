<?php

namespace Entity\Autopilot;

use Entity\Autopilot;
use Entity\Dictionary;
use Entity\Location;
use Entity\User;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="autopilot_taskarchived")
 */
class TaskArchived extends \Entity\BaseEntityDetails {

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Type")
	 */
	protected $type;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $subtype;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $name;

	/**
	 * @var text
	 * @ORM\Column(type="text")
	 */
	protected $mission;

	/**
	 * @var datetime
	 * @ORM\Column(type="datetime")
	 */
	protected $startTime;

	/**
	 * @var datetime
	 * @ORM\Column(type="datetime")
	 */
	protected $due;

	/**
	 * @var time
	 * @ORM\Column(type="time")
	 */
	protected $durationPaid;

	/**
	 * @var json
	 * @ORM\Column(type="json")
	 */
	protected $links;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entity\User\User")
	 */
	protected $user;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entity\Location\Location")
	 */
	protected $userCountry;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entity\Dictionary\Language")
	 */
	protected $userLanguage;

	/**
	 * @var integer
	 * @ORM\Column(type="integer")
	 */
	protected $userLanguageLevel;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entity\User\Role")
	 */
	protected $userRole;

	/**
	 * @var json
	 * @ORM\Column(type="json")
	 */
	protected $validation;

	/**
	 * @var json
	 * @ORM\Column(type="json")
	 */
	protected $actions;

	/**
	 * @var datetime
	 * @ORM\Column(type="datetime")
	 */
	protected $completed;

	







//@entity-generator-code <--- NEMAZAT !!!

	/* ----------------------------- Methods ----------------------------- */		
	public function __construct() {
		parent::__construct();
	}
		
	/**
	 * @param \Entity\Autopilot\Type
	 * @return \Entity\Autopilot\TaskArchived
	 */
	public function setType(\Entity\Autopilot\Type $type) {
		$this->type = $type;

		return $this;
	}
		
	/**
	 * @return \Entity\Autopilot\TaskArchived
	 */
	public function unsetType() {
		$this->type = NULL;

		return $this;
	}
		
	/**
	 * @return \Entity\Autopilot\Type|NULL
	 */
	public function getType() {
		return $this->type;
	}
		
	/**
	 * @param string
	 * @return \Entity\Autopilot\TaskArchived
	 */
	public function setSubtype($subtype) {
		$this->subtype = $subtype;

		return $this;
	}
		
	/**
	 * @return \Entity\Autopilot\TaskArchived
	 */
	public function unsetSubtype() {
		$this->subtype = NULL;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getSubtype() {
		return $this->subtype;
	}
		
	/**
	 * @param string
	 * @return \Entity\Autopilot\TaskArchived
	 */
	public function setName($name) {
		$this->name = $name;

		return $this;
	}
		
	/**
	 * @return \Entity\Autopilot\TaskArchived
	 */
	public function unsetName() {
		$this->name = NULL;

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
	 * @return \Entity\Autopilot\TaskArchived
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
	 * @param \DateTime
	 * @return \Entity\Autopilot\TaskArchived
	 */
	public function setStartTime(\DateTime $startTime) {
		$this->startTime = $startTime;

		return $this;
	}
		
	/**
	 * @return \DateTime|NULL
	 */
	public function getStartTime() {
		return $this->startTime;
	}
		
	/**
	 * @param \DateTime
	 * @return \Entity\Autopilot\TaskArchived
	 */
	public function setDue(\DateTime $due) {
		$this->due = $due;

		return $this;
	}
		
	/**
	 * @return \DateTime|NULL
	 */
	public function getDue() {
		return $this->due;
	}
		
	/**
	 * @param \Extras\Types\Time
	 * @return \Entity\Autopilot\TaskArchived
	 */
	public function setDurationPaid(\Extras\Types\Time $durationPaid) {
		$this->durationPaid = $durationPaid;

		return $this;
	}
		
	/**
	 * @return \Extras\Types\Time|NULL
	 */
	public function getDurationPaid() {
		return $this->durationPaid;
	}
		
	/**
	 * @param json
	 * @return \Entity\Autopilot\TaskArchived
	 */
	public function setLinks($links) {
		$this->links = $links;

		return $this;
	}
		
	/**
	 * @return json|NULL
	 */
	public function getLinks() {
		return $this->links;
	}
		
	/**
	 * @param \Entity\User\User
	 * @return \Entity\Autopilot\TaskArchived
	 */
	public function setUser(\Entity\User\User $user) {
		$this->user = $user;

		return $this;
	}
		
	/**
	 * @return \Entity\Autopilot\TaskArchived
	 */
	public function unsetUser() {
		$this->user = NULL;

		return $this;
	}
		
	/**
	 * @return \Entity\User\User|NULL
	 */
	public function getUser() {
		return $this->user;
	}
		
	/**
	 * @param \Entity\Location\Location
	 * @return \Entity\Autopilot\TaskArchived
	 */
	public function setUserCountry(\Entity\Location\Location $userCountry) {
		$this->userCountry = $userCountry;

		return $this;
	}
		
	/**
	 * @return \Entity\Autopilot\TaskArchived
	 */
	public function unsetUserCountry() {
		$this->userCountry = NULL;

		return $this;
	}
		
	/**
	 * @return \Entity\Location\Location|NULL
	 */
	public function getUserCountry() {
		return $this->userCountry;
	}
		
	/**
	 * @param \Entity\Dictionary\Language
	 * @return \Entity\Autopilot\TaskArchived
	 */
	public function setUserLanguage(\Entity\Dictionary\Language $userLanguage) {
		$this->userLanguage = $userLanguage;

		return $this;
	}
		
	/**
	 * @return \Entity\Autopilot\TaskArchived
	 */
	public function unsetUserLanguage() {
		$this->userLanguage = NULL;

		return $this;
	}
		
	/**
	 * @return \Entity\Dictionary\Language|NULL
	 */
	public function getUserLanguage() {
		return $this->userLanguage;
	}
		
	/**
	 * @param integer
	 * @return \Entity\Autopilot\TaskArchived
	 */
	public function setUserLanguageLevel($userLanguageLevel) {
		$this->userLanguageLevel = $userLanguageLevel;

		return $this;
	}
		
	/**
	 * @return integer|NULL
	 */
	public function getUserLanguageLevel() {
		return $this->userLanguageLevel;
	}
		
	/**
	 * @param \Entity\User\Role
	 * @return \Entity\Autopilot\TaskArchived
	 */
	public function setUserRole(\Entity\User\Role $userRole) {
		$this->userRole = $userRole;

		return $this;
	}
		
	/**
	 * @return \Entity\Autopilot\TaskArchived
	 */
	public function unsetUserRole() {
		$this->userRole = NULL;

		return $this;
	}
		
	/**
	 * @return \Entity\User\Role|NULL
	 */
	public function getUserRole() {
		return $this->userRole;
	}
		
	/**
	 * @param json
	 * @return \Entity\Autopilot\TaskArchived
	 */
	public function setValidation($validation) {
		$this->validation = $validation;

		return $this;
	}
		
	/**
	 * @return json|NULL
	 */
	public function getValidation() {
		return $this->validation;
	}
		
	/**
	 * @param json
	 * @return \Entity\Autopilot\TaskArchived
	 */
	public function setActions($actions) {
		$this->actions = $actions;

		return $this;
	}
		
	/**
	 * @return json|NULL
	 */
	public function getActions() {
		return $this->actions;
	}
		
	/**
	 * @param \DateTime
	 * @return \Entity\Autopilot\TaskArchived
	 */
	public function setCompleted(\DateTime $completed) {
		$this->completed = $completed;

		return $this;
	}
		
	/**
	 * @return \DateTime|NULL
	 */
	public function getCompleted() {
		return $this->completed;
	}
}