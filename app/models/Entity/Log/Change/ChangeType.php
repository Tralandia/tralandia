<?php

namespace Entity\Log\Change;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="log_change_changetype")
 */
class ChangeType extends \Entity\BaseEntity {

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $name;

	/**
	 * @var boolean
	 * @ORM\Column(type="boolean")
	 */
	protected $important;


    



//@entity-generator-code <--- NEMAZAT !!!

	/* ----------------------------- Methods ----------------------------- */		
	public function __construct() {
		parent::__construct();
	}
		
	/**
	 * @param string
	 * @return \Entity\Log\Change\ChangeType
	 */
	public function setName($name) {
		$this->name = $name;

		return $this;
	}
		
	/**
	 * @return \Entity\Log\Change\ChangeType
	 */
	public function unsetName() {
		$this->name = NULL;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getName() {
		return $this->name;
	}
		
	/**
	 * @param boolean
	 * @return \Entity\Log\Change\ChangeType
	 */
	public function setImportant($important) {
		$this->important = $important;

		return $this;
	}
		
	/**
	 * @return boolean|NULL
	 */
	public function getImportant() {
		return $this->important;
	}
}