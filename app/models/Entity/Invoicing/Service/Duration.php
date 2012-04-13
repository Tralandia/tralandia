<?php

namespace Entity\Invoicing\Service;

use Entity\Dictionary;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="invoicing_service_duration")
 */
class Duration extends \Entity\BaseEntity {

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Entity\Dictionary\Phrase", cascade={"persist", "remove"})
	 */
	protected $name;

	/**
	 * @var datetime
	 * @ORM\Column(type="datetime")
	 */
	protected $duration;

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
	 * @param \DateTime
	 * @return \Entity\Invoicing\Service\Duration
	 */
	public function setDuration(\DateTime $duration) {
		$this->duration = $duration;

		return $this;
	}
		
	/**
	 * @return \DateTime|NULL
	 */
	public function getDuration() {
		return $this->duration;
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