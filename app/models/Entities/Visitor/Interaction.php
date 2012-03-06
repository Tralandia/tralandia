<?php

namespace Entities\Visitor;

use Entities\Dictionary;
use Entities\Rental;
use Entities\User;
use Entities\Visitor;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="visitor_interaction")
 */
class Interaction extends \BaseEntityDetails {

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Type")
	 */
	protected $type;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="User\Role")
	 */
	protected $role;

	/**
	 * @var email
	 * @ORM\Column(type="email")
	 */
	protected $senderEmail;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Dictionary\Language")
	 */
	protected $language;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Rental\Rental")
	 */
	protected $rental;

	/**
	 * @var integer
	 * @ORM\Column(type="integer")
	 */
	protected $status;


	public function __construct() {
		parent::__construct();

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
