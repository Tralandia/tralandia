<?php

namespace Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\MappedSuperclass()
 * @ORM\HasLifecycleCallbacks
 */
class BaseEntity extends \Extras\Models\Entity {

	/**
	 * @var integer
	 * @ORM\Id
	 * @ORM\GeneratedValue
	 * @ORM\Column(type="integer")
	 */
	protected $id;

	/**
	 * @var datetime
	 * @ORM\Column(type="datetime")
	 */
	protected $created;

	/**
	 * @var datetime
	 * @ORM\Column(type="datetime")
	 */
	protected $updated;


	public function __construct() {
		parent::__construct();
	}

	/**
	 * @param integer
	 * @return \Entities\BaseEntity
	 */
	public function setId($id) {
		$this->id = $id;

		return $this;
	}

	/**
	 * @return integer|NULL
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @ORM\prePersist
	 * @return \Entities\BaseEntity
	 */
	public function setCreated(){
		$this->created = new \Nette\DateTime;

		return $this;
	}

	/**
	 * @return \Extras\Types\Datetime|NULL
	 */
	public function getCreated() {
		return $this->created;
	}

	/**
	 * @ORM\prePersist
	 * @ORM\preUpdate
	 * @return \Entities\BaseEntity
	 */
	public function setUpdated() {
		if (\Import::$updateDateTime instanceof \Nette\DateTime) {
			$this->updated = \Import::$updateDateTime;
		} else {
			$this->updated = new \Nette\DateTime;
		}
	}

	/**
	 * @return \Extras\Types\Datetime|NULL
	 */
	public function getUpdated() {
		return $this->updated;
	}


}