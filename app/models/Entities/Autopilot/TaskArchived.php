<?php

namespace Entities\Autopilot;

use Entities\Autopilot;
use Entities\Dictionary;
use Entities\Location;
use Entities\User;
use Doctrine\Common\Collections\Collection
use Doctrine\Common\Collections\ArrayCollection


/**
 * @Entity()
 * @Table(name="autopilot_taskarchived")
 */
class TaskArchived extends \BaseEntityDetails {

	/**
	 * @var Collection
	 * @Column(type="Type")
	 */
	protected $type;

	/**
	 * @var string
	 * @Column(type="string", nullable=true)
	 */
	protected $subtype;

	/**
	 * @var string
	 * @Column(type="string", nullable=true)
	 */
	protected $name;

	/**
	 * @var text
	 * @Column(type="text")
	 */
	protected $mission;

	/**
	 * @var datetime
	 * @Column(type="datetime")
	 */
	protected $startTime;

	/**
	 * @var time
	 * @Column(type="time")
	 */
	protected $durationPaid;

	/**
	 * @var json
	 * @Column(type="json")
	 */
	protected $links;

	/**
	 * @var Collection
	 * @OneToOne(targetEntity="User\User")
	 */
	protected $user;

	/**
	 * @var Collection
	 * @OneToOne(targetEntity="Location\Location")
	 */
	protected $userCountry;

	/**
	 * @var Collection
	 * @OneToOne(targetEntity="Dictionary\Language")
	 */
	protected $userLanguage;

	/**
	 * @var Collection
	 * @OneToOne(targetEntity="Dictionary\Quality")
	 */
	protected $userLanguageQuality;

	/**
	 * @var Collection
	 * @OneToOne(targetEntity="User\Role")
	 */
	protected $userRole;

	/**
	 * @var json
	 * @Column(type="json")
	 */
	protected $validation;

	/**
	 * @var json
	 * @Column(type="json")
	 */
	protected $actions;


	public function __construct() {

	}


	/**
	 * @param Type $type
	 * @return TaskArchived
	 */
	public function setType(Type  $type) {
		$this->type = $type;
		return $this;
	}


	/**
	 * @return Type
	 */
	public function getType() {
		return $this->type;
	}


	/**
	 * @param string $subtype
	 * @return TaskArchived
	 */
	public function setSubtype($subtype) {
		$this->subtype = $subtype;
		return $this;
	}


	/**
	 * @return string
	 */
	public function getSubtype() {
		return $this->subtype;
	}


	/**
	 * @param string $name
	 * @return TaskArchived
	 */
	public function setName($name) {
		$this->name = $name;
		return $this;
	}


	/**
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}


	/**
	 * @param text $mission
	 * @return TaskArchived
	 */
	public function setMission($mission) {
		$this->mission = $mission;
		return $this;
	}


	/**
	 * @return text
	 */
	public function getMission() {
		return $this->mission;
	}


	/**
	 * @param datetime $startTime
	 * @return TaskArchived
	 */
	public function setStartTime($startTime) {
		$this->startTime = $startTime;
		return $this;
	}


	/**
	 * @return datetime
	 */
	public function getStartTime() {
		return $this->startTime;
	}


	/**
	 * @param time $durationPaid
	 * @return TaskArchived
	 */
	public function setDurationPaid($durationPaid) {
		$this->durationPaid = $durationPaid;
		return $this;
	}


	/**
	 * @return time
	 */
	public function getDurationPaid() {
		return $this->durationPaid;
	}


	/**
	 * @param json $links
	 * @return TaskArchived
	 */
	public function setLinks($links) {
		$this->links = $links;
		return $this;
	}


	/**
	 * @return json
	 */
	public function getLinks() {
		return $this->links;
	}


	/**
	 * @param User\User $user
	 * @return TaskArchived
	 */
	public function setUser(User\User  $user) {
		$this->user = $user;
		return $this;
	}


	/**
	 * @return User\User
	 */
	public function getUser() {
		return $this->user;
	}


	/**
	 * @param Location\Location $userCountry
	 * @return TaskArchived
	 */
	public function setUserCountry(Location\Location  $userCountry) {
		$this->userCountry = $userCountry;
		return $this;
	}


	/**
	 * @return Location\Location
	 */
	public function getUserCountry() {
		return $this->userCountry;
	}


	/**
	 * @param Dictionary\Language $userLanguage
	 * @return TaskArchived
	 */
	public function setUserLanguage(Dictionary\Language  $userLanguage) {
		$this->userLanguage = $userLanguage;
		return $this;
	}


	/**
	 * @return Dictionary\Language
	 */
	public function getUserLanguage() {
		return $this->userLanguage;
	}


	/**
	 * @param Dictionary\Quality $userLanguageQuality
	 * @return TaskArchived
	 */
	public function setUserLanguageQuality(Dictionary\Quality  $userLanguageQuality) {
		$this->userLanguageQuality = $userLanguageQuality;
		return $this;
	}


	/**
	 * @return Dictionary\Quality
	 */
	public function getUserLanguageQuality() {
		return $this->userLanguageQuality;
	}


	/**
	 * @param User\Role $userRole
	 * @return TaskArchived
	 */
	public function setUserRole(User\Role  $userRole) {
		$this->userRole = $userRole;
		return $this;
	}


	/**
	 * @return User\Role
	 */
	public function getUserRole() {
		return $this->userRole;
	}


	/**
	 * @param json $validation
	 * @return TaskArchived
	 */
	public function setValidation($validation) {
		$this->validation = $validation;
		return $this;
	}


	/**
	 * @return json
	 */
	public function getValidation() {
		return $this->validation;
	}


	/**
	 * @param json $actions
	 * @return TaskArchived
	 */
	public function setActions($actions) {
		$this->actions = $actions;
		return $this;
	}


	/**
	 * @return json
	 */
	public function getActions() {
		return $this->actions;
	}

}
