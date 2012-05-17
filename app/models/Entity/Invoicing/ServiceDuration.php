<?php

namespace Entity\Invoicing;

use Entity\Dictionary;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="invoicing_service_duration")
 */
class ServiceDuration extends \Entity\BaseEntity {

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $duration;

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Entity\Dictionary\Phrase", cascade={"persist", "remove"})
	 */
	protected $name;

	/**
	 * @var integer
	 * @ORM\Column(type="integer")
	 */
	protected $sort;

	




















//@entity-generator-code <--- NEMAZAT !!!

	/* ----------------------------- Methods ----------------------------- */		
	public function __construct() {
		parent::__construct();
	}
		
	/**
	 * @param string
	 * @return \Entity\Invoicing\Service\Duration
	 */
	public function setDuration($duration) {
		$this->duration = $duration;

		return $this;
	}
		
	/**
	 * @return \Entity\Invoicing\Service\Duration
	 */
	public function unsetDuration() {
		$this->duration = NULL;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getDuration() {
		return $this->duration;
	}
		
	/**
	 * @param \Entity\Dictionary\Phrase
	 * @return \Entity\Invoicing\Service\Duration
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
		
	/**
	 * @param integer
	 * @return \Entity\Invoicing\Service\Duration
	 */
	public function setSort($sort) {
		$this->sort = $sort;

		return $this;
	}
		
	/**
	 * @return integer|NULL
	 */
	public function getSort() {
		return $this->sort;
	}
}