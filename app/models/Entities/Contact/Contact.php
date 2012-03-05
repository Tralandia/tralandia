<?php

namespace Contact;

use Contact;
use Doctrine\Common\Collections\Collection
use Doctrine\Common\Collections\ArrayCollection


/**
 * @Entity()
 * @Table(name="contact_contact")
 */
class Contact extends \BaseEntity {

	/**
	 * @var Collection
	 * @Column(type="Type")
	 */
	protected $type;

	/**
	 * @var string
	 * @Column(type="string", nullable=true)
	 */
	protected $value;

	/**
	 * @var json
	 * @Column(type="json")
	 */
	protected $info;

	/**
	 * @var boolean
	 * @Column(type="boolean")
	 */
	protected $unsubscribed;

	/**
	 * @var boolean
	 * @Column(type="boolean")
	 */
	protected $banned;

	/**
	 * @var boolean
	 * @Column(type="boolean")
	 */
	protected $full;

	/**
	 * @var boolean
	 * @Column(type="boolean")
	 */
	protected $spam;


	public function __construct() {

	}


	/**
	 * @param Type $type
	 * @return Contact
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
	 * @param string $value
	 * @return Contact
	 */
	public function setValue($value) {
		$this->value = $value;
		return $this;
	}


	/**
	 * @return string
	 */
	public function getValue() {
		return $this->value;
	}


	/**
	 * @param json $info
	 * @return Contact
	 */
	public function setInfo($info) {
		$this->info = $info;
		return $this;
	}


	/**
	 * @return json
	 */
	public function getInfo() {
		return $this->info;
	}


	/**
	 * @param boolean $unsubscribed
	 * @return Contact
	 */
	public function setUnsubscribed($unsubscribed) {
		$this->unsubscribed = $unsubscribed;
		return $this;
	}


	/**
	 * @return boolean
	 */
	public function getUnsubscribed() {
		return $this->unsubscribed;
	}


	/**
	 * @param boolean $banned
	 * @return Contact
	 */
	public function setBanned($banned) {
		$this->banned = $banned;
		return $this;
	}


	/**
	 * @return boolean
	 */
	public function getBanned() {
		return $this->banned;
	}


	/**
	 * @param boolean $full
	 * @return Contact
	 */
	public function setFull($full) {
		$this->full = $full;
		return $this;
	}


	/**
	 * @return boolean
	 */
	public function getFull() {
		return $this->full;
	}


	/**
	 * @param boolean $spam
	 * @return Contact
	 */
	public function setSpam($spam) {
		$this->spam = $spam;
		return $this;
	}


	/**
	 * @return boolean
	 */
	public function getSpam() {
		return $this->spam;
	}

}
