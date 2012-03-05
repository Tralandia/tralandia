<?php

namespace Autopilot;



/**
 * @Entity()
 * @Table(name="autopilot_type")
 */
class Type extends \BaseEntityDetails {

	/**
	 * @var string
	 * @Column(type="string", nullable=true)
	 */
	protected $name;

	/**
	 * @var time
	 * @Column(type="time")
	 */
	protected $durationPaid;

	/**
	 * @var json
	 * @Column(type="json")
	 */
	protected $validation;

	/**
	 * @var integer
	 * @Column(type="integer")
	 */
	protected $stackable;


	public function __construct() {

	}


	/**
	 * @param string $name
	 * @return Type
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
	 * @param time $durationPaid
	 * @return Type
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
	 * @param json $validation
	 * @return Type
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
	 * @param integer $stackable
	 * @return Type
	 */
	public function setStackable($stackable) {
		$this->stackable = $stackable;
		return $this;
	}


	/**
	 * @return integer
	 */
	public function getStackable() {
		return $this->stackable;
	}

}
