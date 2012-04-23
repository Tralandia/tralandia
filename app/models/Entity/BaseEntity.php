<?php

namespace Entity;

use Doctrine\ORM\Mapping as ORM;
use	Extras\UI as UI;

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
	 * @UI\Control(type="hidden")
	 */
	protected $id;

	/**
	 * @var integer
	 * @ORM\Column(type="integer", nullable=true)
	 */
	protected $oldId;

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
	 * @return \Entity\BaseEntity
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
	 * @param integer
	 * @return \Entity\BaseEntity
	 */
	public function setOldId($oldId) {
		$this->oldId = $oldId;

		return $this;
	}

	/**
	 * @return \Entity\BaseEntity
	 */
	public function unsetOldId() {
		$this->oldId = NULL;

		return $this;
	}

	/**
	 * @return integer|NULL
	 */
	public function getOldId() {
		return $this->oldId;
	}

	/**
	 * @ORM\prePersist
	 * @return \Entity\BaseEntity
	 */
	public function setCreated($datetime = NULL){
		$this->created = new \Nette\DateTime($datetime);

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
	 * @return \Entity\BaseEntity
	 */
	public function setUpdated() {
		$this->updated = new \Nette\DateTime;
	}

	/**
	 * @return \Extras\Types\Datetime|NULL
	 */
	public function getUpdated() {
		return $this->updated;
	}


}