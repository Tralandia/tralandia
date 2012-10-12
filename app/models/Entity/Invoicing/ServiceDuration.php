<?php

namespace Entity\Invoicing;

use Entity\Dictionary;
use Doctrine\ORM\Mapping as ORM;
use	Extras\Annotation as EA;

/**
 * @ORM\Entity()
 * @ORM\Table(name="invoicing_serviceduration")
 * @EA\Service(name="\Service\Invoicing\ServiceDuration")
 * @EA\ServiceList(name="\Service\Invoicing\ServiceDurationList")
 * @EA\Primary(key="id", value="duration")
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

	




















	//@entity-generator-code --- NEMAZAT !!!

	/* ----------------------------- Methods ----------------------------- */		
	public function __construct()
	{
		parent::__construct();
	}
		
	/**
	 * @param string
	 * @return \Entity\Invoicing\ServiceDuration
	 */
	public function setDuration($duration)
	{
		$this->duration = $duration;

		return $this;
	}
		
	/**
	 * @return \Entity\Invoicing\ServiceDuration
	 */
	public function unsetDuration()
	{
		$this->duration = NULL;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getDuration()
	{
		return $this->duration;
	}
		
	/**
	 * @param \Entity\Dictionary\Phrase
	 * @return \Entity\Invoicing\ServiceDuration
	 */
	public function setName(\Entity\Dictionary\Phrase $name)
	{
		$this->name = $name;

		return $this;
	}
		
	/**
	 * @return \Entity\Dictionary\Phrase|NULL
	 */
	public function getName()
	{
		return $this->name;
	}
		
	/**
	 * @param integer
	 * @return \Entity\Invoicing\ServiceDuration
	 */
	public function setSort($sort)
	{
		$this->sort = $sort;

		return $this;
	}
		
	/**
	 * @return integer|NULL
	 */
	public function getSort()
	{
		return $this->sort;
	}
}