<?php

namespace Entity\Visitor;

use Entity\Dictionary;
use Entity\Rental;
use Entity\User;
use Entity\Visitor;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="visitor_interaction")
 */
class Interaction extends \Entity\BaseEntityDetails {

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Type")
	 */
	protected $type;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entity\User\Role")
	 */
	protected $role;

	/**
	 * @var email
	 * @ORM\Column(type="email")
	 */
	protected $senderEmail;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entity\Dictionary\Language")
	 */
	protected $language;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entity\Rental\Rental")
	 */
	protected $rental;

	/**
	 * @var integer
	 * @ORM\Column(type="integer")
	 */
	protected $status;

	



















//@entity-generator-code <--- NEMAZAT !!!

	/* ----------------------------- Methods ----------------------------- */		
	public function __construct() {
		parent::__construct();
	}
		
	/**
	 * @param \Entity\Visitor\Type
	 * @return \Entity\Visitor\Interaction
	 */
	public function setType(\Entity\Visitor\Type $type) {
		$this->type = $type;

		return $this;
	}
		
	/**
	 * @return \Entity\Visitor\Interaction
	 */
	public function unsetType() {
		$this->type = NULL;

		return $this;
	}
		
	/**
	 * @return \Entity\Visitor\Type|NULL
	 */
	public function getType() {
		return $this->type;
	}
		
	/**
	 * @param \Entity\User\Role
	 * @return \Entity\Visitor\Interaction
	 */
	public function setRole(\Entity\User\Role $role) {
		$this->role = $role;

		return $this;
	}
		
	/**
	 * @return \Entity\Visitor\Interaction
	 */
	public function unsetRole() {
		$this->role = NULL;

		return $this;
	}
		
	/**
	 * @return \Entity\User\Role|NULL
	 */
	public function getRole() {
		return $this->role;
	}
		
	/**
	 * @param \Extras\Types\Email
	 * @return \Entity\Visitor\Interaction
	 */
	public function setSenderEmail(\Extras\Types\Email $senderEmail) {
		$this->senderEmail = $senderEmail;

		return $this;
	}
		
	/**
	 * @return \Extras\Types\Email|NULL
	 */
	public function getSenderEmail() {
		return $this->senderEmail;
	}
		
	/**
	 * @param \Entity\Dictionary\Language
	 * @return \Entity\Visitor\Interaction
	 */
	public function setLanguage(\Entity\Dictionary\Language $language) {
		$this->language = $language;

		return $this;
	}
		
	/**
	 * @return \Entity\Visitor\Interaction
	 */
	public function unsetLanguage() {
		$this->language = NULL;

		return $this;
	}
		
	/**
	 * @return \Entity\Dictionary\Language|NULL
	 */
	public function getLanguage() {
		return $this->language;
	}
		
	/**
	 * @param \Entity\Rental\Rental
	 * @return \Entity\Visitor\Interaction
	 */
	public function setRental(\Entity\Rental\Rental $rental) {
		$this->rental = $rental;

		return $this;
	}
		
	/**
	 * @return \Entity\Visitor\Interaction
	 */
	public function unsetRental() {
		$this->rental = NULL;

		return $this;
	}
		
	/**
	 * @return \Entity\Rental\Rental|NULL
	 */
	public function getRental() {
		return $this->rental;
	}
		
	/**
	 * @param integer
	 * @return \Entity\Visitor\Interaction
	 */
	public function setStatus($status) {
		$this->status = $status;

		return $this;
	}
		
	/**
	 * @return integer|NULL
	 */
	public function getStatus() {
		return $this->status;
	}
}