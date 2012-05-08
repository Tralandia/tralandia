<?php

namespace Entity\Medium;

use Entity\Dictionary;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="medium_type")
 */
class Type extends \Entity\BaseEntityDetails {

	/**
	 * @var text
	 * @ORM\Column(type="text")
	 */
	protected $name;














//@entity-generator-code <--- NEMAZAT !!!

	/* ----------------------------- Methods ----------------------------- */		
	public function __construct() {
		parent::__construct();
	}
		
	/**
	 * @param string
	 * @return \Entity\Medium\Type
	 */
	public function setName($name) {
		$this->name = $name;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getName() {
		return $this->name;
	}
}