<?php

namespace Entity\Invoicing\Service;

use Entity\Dictionary;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="invoicing_service_type")
 */
class Type extends \Entity\BaseEntity {

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $nameTechnical;

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Entity\Dictionary\Phrase", cascade={"persist", "remove"})
	 */
	protected $name;

	







//@entity-generator-code <--- NEMAZAT !!!

	/* ----------------------------- Methods ----------------------------- */		
	public function __construct() {
		parent::__construct();
	}
		
	/**
	 * @param string
	 * @return \Entity\Invoicing\Service\Type
	 */
	public function setNameTechnical($nameTechnical) {
		$this->nameTechnical = $nameTechnical;

		return $this;
	}
		
	/**
	 * @return \Entity\Invoicing\Service\Type
	 */
	public function unsetNameTechnical() {
		$this->nameTechnical = NULL;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getNameTechnical() {
		return $this->nameTechnical;
	}
		
	/**
	 * @param \Entity\Dictionary\Phrase
	 * @return \Entity\Invoicing\Service\Type
	 */
	public function setName(\Entity\Dictionary\Phrase $name) {
		$this->name = $name;

		return $this;
	}
		
	/**
	 * @return \Entity\Dictionary\Phrase|NULL
	 */
	public function getName() {
		return $this->name;
	}
}