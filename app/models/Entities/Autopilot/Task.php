<?php

namespace Entities\Autopilot;

use Entities\Dictionary;
use Entities\Location;
use Entities\User;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="autopilot_task")
 */
class Task extends \Entities\BaseEntityDetails {

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
	 * @var float
	 * @ORM\Column(type="float")
	 */
	protected $durationPaid;

	/**
	 * @var json
	 * @ORM\Column(type="json", nullable=true)
	 */
	protected $links;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entities\User\User")
	 */
	protected $user;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entities\Location\Location")
	 */
	protected $userCountry;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entities\Dictionary\Language")
	 */
	protected $userLanguage;

	/**
	 * @var integer
	 * @ORM\Column(type="integer", nullable=true)
	 */
	protected $userLanguageLevel;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entities\User\Role")
	 */
	protected $userRole;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Entities\User\User", mappedBy="tasks")
	 */
	protected $usersExcluded;

	/**
	 * @var json
	 * @ORM\Column(type="json", nullable=true)
	 */
	protected $validation;

	/**
	 * @var json
	 * @ORM\Column(type="json", nullable=true)
	 */
	protected $actions;

	/* ----------------------------- Methods ----------------------------- */


	public function __construct() {
		parent::__construct();

		$this->usersExcluded = new \Doctrine\Common\Collections\ArrayCollection;
	}

	/**
	 * @param \Entities\Autopilot\Type
	 * @return \Entities\Autopilot\Task
	 */
	public function setType(\Entities\Autopilot\Type $type) {
		$this->type = $type;

		return $this;
	}

	/**
	 * @return \Entities\Autopilot\Task
	 */
	public function unsetType() {
		$this->type = NULL;

		return $this;
	}

	/**
	 * @return \Entities\Autopilot\Type|NULL
	 */
	public function getType() {
		return $this->type;
	}

	/**
	 * @param string
	 * @return \Entities\Autopilot\Task
	 */
	public function setSubtype($subtype) {
		$this->subtype = $subtype;

		return $this;
	}

	/**
	 * @return \Entities\Autopilot\Task
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
	 * @return \Entities\Autopilot\Task
	 */
	public function setName($name) {
		$this->name = $name;

		return $this;
	}

	/**
	 * @return \Entities\Autopilot\Task
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
	 * @return \Entities\Autopilot\Task
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
	 * @param \Nette\Datetime
	 * @return \Entities\Autopilot\Task
	 */
	public function setStartTime(\Nette\Datetime $startTime) {
		$this->startTime = $startTime;

		return $this;
	}

	/**
	 * @return \Nette\Datetime|NULL
	 */
	public function getStartTime() {
		return $this->startTime;
	}

	/**
	 * @param \Nette\Datetime
	 * @return \Entities\Autopilot\Task
	 */
	public function setDue(\Nette\Datetime $due) {
		$this->due = $due;

		return $this;
	}

	/**
	 * @return \Nette\Datetime|NULL
	 */
	public function getDue() {
		return $this->due;
	}

	/**
	 * @param float
	 * @return \Entities\Autopilot\Task
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
	 * @return \Entities\Autopilot\Task
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
	 * @param \Entities\User\User
	 * @return \Entities\Autopilot\Task
	 */
	public function setUser(\Entities\User\User $user) {
		$this->user = $user;

		return $this;
	}

	/**
	 * @return \Entities\Autopilot\Task
	 */
	public function unsetUser() {
		$this->user = NULL;

		return $this;
	}

	/**
	 * @return \Entities\User\User|NULL
	 */
	public function getUser() {
		return $this->user;
	}

	/**
	 * @param \Entities\Location\Location
	 * @return \Entities\Autopilot\Task
	 */
	public function setUserCountry(\Entities\Location\Location $userCountry) {
		$this->userCountry = $userCountry;

		return $this;
	}

	/**
	 * @return \Entities\Autopilot\Task
	 */
	public function unsetUserCountry() {
		$this->userCountry = NULL;

		return $this;
	}

	/**
	 * @return \Entities\Location\Location|NULL
	 */
	public function getUserCountry() {
		return $this->userCountry;
	}

	/**
	 * @param \Entities\Dictionary\Language
	 * @return \Entities\Autopilot\Task
	 */
	public function setUserLanguage(\Entities\Dictionary\Language $userLanguage) {
		$this->userLanguage = $userLanguage;

		return $this;
	}

	/**
	 * @return \Entities\Autopilot\Task
	 */
	public function unsetUserLanguage() {
		$this->userLanguage = NULL;

		return $this;
	}

	/**
	 * @return \Entities\Dictionary\Language|NULL
	 */
	public function getUserLanguage() {
		return $this->userLanguage;
	}

	/**
	 * @param integer
	 * @return \Entities\Autopilot\Task
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
	 * @param \Entities\User\Role
	 * @return \Entities\Autopilot\Task
	 */
	public function setUserRole(\Entities\User\Role $userRole) {
		$this->userRole = $userRole;

		return $this;
	}

	/**
	 * @return \Entities\Autopilot\Task
	 */
	public function unsetUserRole() {
		$this->userRole = NULL;

		return $this;
	}

	/**
	 * @return \Entities\User\Role|NULL
	 */
	public function getUserRole() {
		return $this->userRole;
	}

	/**
	 * @param \Entities\User\User
	 * @return \Entities\Autopilot\Task
	 */
	public function addUsersExcluded(\Entities\User\User $usersExcluded) {
		if(!$this->usersExcluded->contains($usersExcluded)) {
			$this->usersExcluded->add($usersExcluded);
		}
		$usersExcluded->addTask($this);

		return $this;
	}

	/**
	 * @param \Entities\User\User
	 * @return \Entities\Autopilot\Task
	 */
	public function removeUsersExcluded(\Entities\User\User $usersExcluded) {
		if($this->usersExcluded->contains($usersExcluded)) {
			$this->usersExcluded->removeElement($usersExcluded);
		}
		$usersExcluded->removeTask($this);

		return $this;
	}

	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection of \Entities\User\User
	 */
	public function getUsersExcluded() {
		return $this->usersExcluded;
	}

	/**
	 * @param json
	 * @return \Entities\Autopilot\Task
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
	 * @return \Entities\Autopilot\Task
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

}