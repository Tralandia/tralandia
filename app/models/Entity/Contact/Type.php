<?php

namespace Entity\Contact;

use Entity\Dictionary;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="contact_type")
 */
class Type extends \Entity\BaseEntity {

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Entity\Dictionary\Phrase", cascade={"persist", "remove"})
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
	 * @param \Entity\Dictionary\Phrase
	 * @return \Entity\Contact\Type
	 */
	public function setName(\Entity\Dictionary\Phrase $name) {
		$this->name = $name;

		return $this;
	}

	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection of \Entity\Dictionary\Phrase
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @param string
	 * @return \Entity\Contact\Type
	 */
	public function setClass($class) {
		$this->class = $class;

		return $this;
	}

	/**
	 * @return \Entity\Contact\Type
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

}