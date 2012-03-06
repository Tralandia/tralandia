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
	 * @ORM\ManyToMany(type="ChangeType")
	 */
	protected $type;

	/**
	 * @var string
	 * @ORM\ManyToMany(type="string", nullable=true)
	 */
	protected $entityName;

	/**
	 * @var integer
	 * @ORM\ManyToMany(type="integer")
	 */
	protected $entityId;


	public function __construct() {
		parent::__construct();

	}


	/**
	 * @param ChangeType $type
	 * @return ChangeLog
	 */
	public function setType(ChangeType  $type) {
		$this->type = $type;
		return $this;
	}


	/**
	 * @return ChangeType
	 */
	public function getType() {
		return $this->type;
	}


	/**
	 * @param string $entityName
	 * @return ChangeLog
	 */
	public function setEntityName($entityName) {
		$this->entityName = $entityName;
		return $this;
	}


	/**
	 * @return string
	 */
	public function getEntityName() {
		return $this->entityName;
	}


	/**
	 * @param integer $entityId
	 * @return ChangeLog
	 */
	public function setEntityId($entityId) {
		$this->entityId = $entityId;
		return $this;
	}


	/**
	 * @return integer
	 */
	public function getEntityId() {
		return $this->entityId;
	}

}
