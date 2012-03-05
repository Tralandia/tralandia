<?php

namespace Visitor;

use Dictionary;
use Rental;
use User;
use Visitor;
use Doctrine\Common\Collections\Collection
use Doctrine\Common\Collections\ArrayCollection


/**
 * @Entity()
 * @Table(name="visitor_interaction")
 */
class Interaction extends \BaseEntityDetails {

	/**
	 * @var Collection
	 * @Column(type="Type")
	 */
	protected $type;

	/**
	 * @var Collection
	 * @ManyToMany(targetEntity="User\Role")
	 */
	protected $role;

	/**
	 * @var email
	 * @Column(type="email")
	 */
	protected $senderEmail;

	/**
	 * @var Collection
	 * @ManyToMany(targetEntity="Dictionary\Language")
	 */
	protected $language;

	/**
	 * @var Collection
	 * @ManyToMany(targetEntity="Rental\Rental")
	 */
	protected $rental;

	/**
	 * @var integer
	 * @Column(type="integer")
	 */
	protected $status;


	public function __construct() {

	}


	/**
	 * @param Type $type
	 * @return Interaction
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
	 * @param User\Role $role
	 * @return Interaction
	 */
	public function setRole(User\Role  $role) {
		$this->role = $role;
		return $this;
	}


	/**
	 * @return User\Role
	 */
	public function getRole() {
		return $this->role;
	}


	/**
	 * @param email $senderEmail
	 * @return Interaction
	 */
	public function setSenderEmail($senderEmail) {
		$this->senderEmail = $senderEmail;
		return $this;
	}


	/**
	 * @return email
	 */
	public function getSenderEmail() {
		return $this->senderEmail;
	}


	/**
	 * @param Dictionary\Language $language
	 * @return Interaction
	 */
	public function setLanguage(Dictionary\Language  $language) {
		$this->language = $language;
		return $this;
	}


	/**
	 * @return Dictionary\Language
	 */
	public function getLanguage() {
		return $this->language;
	}


	/**
	 * @param Rental\Rental $rental
	 * @return Interaction
	 */
	public function setRental(Rental\Rental  $rental) {
		$this->rental = $rental;
		return $this;
	}


	/**
	 * @return Rental\Rental
	 */
	public function getRental() {
		return $this->rental;
	}


	/**
	 * @param integer $status
	 * @return Interaction
	 */
	public function setStatus($status) {
		$this->status = $status;
		return $this;
	}


	/**
	 * @return integer
	 */
	public function getStatus() {
		return $this->status;
	}

}
