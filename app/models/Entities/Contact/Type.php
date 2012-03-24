<?php

namespace Entities\Contact;

use Entities\Dictionary;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="contact_type")
 */
class Type extends \Entities\BaseEntity {

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Entities\Dictionary\Phrase", cascade={"persist", "remove"})
	 */
	protected $name;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $class;


	public function __construct() {
		parent::__construct();
	}

	/**
	 * @param \Entities\Dictionary\Phrase
	 * @return \Entities\Contact\Type
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
	 * @param string
	 * @return \Entities\Contact\Type
	 */
	public function setClass($class) {
		$this->class = $class;

		return $this;
	}

	/**
	 * @return \Entities\Contact\Type
	 */
	public function unsetClass() {
		$this->class = NULL;

		return $this;
	}

	/**
	 * @return string|NULL
	 */
	public function getClass() {
		return $this->class;
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