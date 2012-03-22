<?php

namespace Entities\Location;

use Entities\Dictionary;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="location_type")
 */
class Type extends \Entities\BaseEntityDetails {

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Entities\Dictionary\Phrase", cascade={"persist", "remove"})
	 */
	protected $name;
	
	public function __construct() {
		parent::__construct();
	}

	/**
	 * @param \Entities\Dictionary\Phrase
	 * @return \Entities\Location\Type
	 */
	public function setName(\Entities\Dictionary\Phrase $name) {
		$this->name = $name;

		return $this;
	}

	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection of \Entities\Dictionary\Phrase
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @param integer
	 * @return \Entities\BaseEntity
	 */
	public function setOldId($oldId) {
		$this->oldId = $oldId;

		return $this;
	}

	/**
	 * @return \Entities\BaseEntity
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

}