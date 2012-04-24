<?php

namespace Entity\Log\Change;

use Entity\Log;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="log_change_changelog")
 */
class ChangeLog extends \Entity\BaseEntityDetails {

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="ChangeType")
	 */
	protected $type;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $entityName;

	/**
	 * @var email
	 * @ORM\Column(type="email")
	 */
    protected $userEmail;

	/**
	 * @var integer
	 * @ORM\Column(type="integer")
	 */
	protected $entityId;

 
    
















//@entity-generator-code <--- NEMAZAT !!!

	/* ----------------------------- Methods ----------------------------- */		
	public function __construct() {
		parent::__construct();
	}
		
	/**
	 * @param \Entity\Log\Change\ChangeType
	 * @return \Entity\Log\Change\ChangeLog
	 */
	public function setType(\Entity\Log\Change\ChangeType $type) {
		$this->type = $type;

		return $this;
	}
		
	/**
	 * @return \Entity\Log\Change\ChangeLog
	 */
	public function unsetType() {
		$this->type = NULL;

		return $this;
	}
		
	/**
	 * @return \Entity\Log\Change\ChangeType|NULL
	 */
	public function getType() {
		return $this->type;
	}
		
	/**
	 * @param string
	 * @return \Entity\Log\Change\ChangeLog
	 */
	public function setEntityName($entityName) {
		$this->entityName = $entityName;

		return $this;
	}
		
	/**
	 * @return \Entity\Log\Change\ChangeLog
	 */
	public function unsetEntityName() {
		$this->entityName = NULL;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getEntityName() {
		return $this->entityName;
	}
		
	/**
	 * @param \Extras\Types\Email
	 * @return \Entity\Log\Change\ChangeLog
	 */
	public function setUserEmail(\Extras\Types\Email $userEmail) {
		$this->userEmail = $userEmail;

		return $this;
	}
		
	/**
	 * @return \Extras\Types\Email|NULL
	 */
	public function getUserEmail() {
		return $this->userEmail;
	}
		
	/**
	 * @param integer
	 * @return \Entity\Log\Change\ChangeLog
	 */
	public function setEntityId($entityId) {
		$this->entityId = $entityId;

		return $this;
	}
		
	/**
	 * @return integer|NULL
	 */
	public function getEntityId() {
		return $this->entityId;
	}
}