<?php

namespace Entities\Log\Change;



/**
 * @ORM\Entity()
 * @ORM\Table(name="log_change_changetype")
 */
class ChangeType extends \BaseEntity {

	/**
	 * @var string
	 * @ORM\ManyToMany(type="string", nullable=true)
	 */
	protected $name;

	/**
	 * @var boolean
	 * @ORM\ManyToMany(type="boolean")
	 */
	protected $important;


	public function __construct() {

	}


	/**
	 * @param string $name
	 * @return ChangeType
	 */
	public function setName($name) {
		$this->name = $name;
		return $this;
	}


	/**
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}


	/**
	 * @param boolean $important
	 * @return ChangeType
	 */
	public function setImportant($important) {
		$this->important = $important;
		return $this;
	}


	/**
	 * @return boolean
	 */
	public function getImportant() {
		return $this->important;
	}

}
