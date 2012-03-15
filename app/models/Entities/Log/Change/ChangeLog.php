<?php

namespace Entities\Log\Change;

use Entities\Log;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="log_change_changelog")
 */
class ChangeLog extends \BaseEntityDetails {

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

    public function __construct() {
        parent::__construct();
    }

    /**
     * @param \Entities\Log\Change\ChangeType
     * @return \Entities\Log\Change\ChangeLog
     */
    public function setType(\Entities\Log\Change\ChangeType $type) {
        $this->type = $type;

        return $this;
    }

    /**
     * @return \Entities\Log\Change\ChangeLog
     */
    public function unsetType() {
        $this->type = NULL;

        return $this;
    }

    /**
     * @return \Entities\Log\Change\ChangeType|NULL
     */
    public function getType() {
        return $this->type;
    }

    /**
     * @param string
     * @return \Entities\Log\Change\ChangeLog
     */
    public function setEntityName($entityName) {
        $this->entityName = $entityName;

        return $this;
    }

    /**
     * @return \Entities\Log\Change\ChangeLog
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
     * @return \Entities\Log\Change\ChangeLog
     */
    public function setUserEmail(\Extras\Types\Email $userEmail) {
        $this->userEmail = $userEmail;

        return $this;
    }

    /**
     * @return \Entities\Log\Change\ChangeLog
     */
    public function unsetUserEmail() {
        $this->userEmail = NULL;

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
     * @return \Entities\Log\Change\ChangeLog
     */
    public function setEntityId($entityId) {
        $this->entityId = $entityId;

        return $this;
    }

    /**
     * @return \Entities\Log\Change\ChangeLog
     */
    public function unsetEntityId() {
        $this->entityId = NULL;

        return $this;
    }

    /**
     * @return integer|NULL
     */
    public function getEntityId() {
        return $this->entityId;
    }

}