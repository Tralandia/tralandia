<?php

namespace Entity\Invoicing;

use Entity\Invoicing;
use Doctrine\ORM\Mapping as ORM;
use	Extras\Annotation as EA;

/**
 * @ORM\Entity()
 * @ORM\Table(name="invoicing_service")
 * @EA\Primary(key="id", value="id")
 */
class Service extends \Entity\BaseEntity {

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="ServiceType")
	 */
	protected $type;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="ServiceDuration")
	 */
	protected $duration;

	/**
	 * @var price
	 * @ORM\Column(type="price")
	 */
	protected $defaultPrice;

	/**
	 * @var price
	 * @ORM\Column(type="price")
	 */
	protected $currentPrice;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entity\Invoicing\Package", inversedBy="services")
	 */
	protected $package;
	




















	//@entity-generator-code --- NEMAZAT !!!

	/* ----------------------------- Methods ----------------------------- */		
	public function __construct()
	{
		parent::__construct();
	}
		
	/**
	 * @param \Entity\Invoicing\ServiceType
	 * @return \Entity\Invoicing\Service
	 */
	public function setType(\Entity\Invoicing\ServiceType $type)
	{
		$this->type = $type;

		return $this;
	}
		
	/**
	 * @return \Entity\Invoicing\Service
	 */
	public function unsetType()
	{
		$this->type = NULL;

		return $this;
	}
		
	/**
	 * @return \Entity\Invoicing\ServiceType|NULL
	 */
	public function getType()
	{
		return $this->type;
	}
		
	/**
	 * @param \Entity\Invoicing\ServiceDuration
	 * @return \Entity\Invoicing\Service
	 */
	public function setDuration(\Entity\Invoicing\ServiceDuration $duration)
	{
		$this->duration = $duration;

		return $this;
	}
		
	/**
	 * @return \Entity\Invoicing\Service
	 */
	public function unsetDuration()
	{
		$this->duration = NULL;

		return $this;
	}
		
	/**
	 * @return \Entity\Invoicing\ServiceDuration|NULL
	 */
	public function getDuration()
	{
		return $this->duration;
	}
		
	/**
	 * @param \Extras\Types\Price
	 * @return \Entity\Invoicing\Service
	 */
	public function setDefaultPrice(\Extras\Types\Price $defaultPrice)
	{
		$this->defaultPrice = $defaultPrice;

		return $this;
	}
		
	/**
	 * @return \Extras\Types\Price|NULL
	 */
	public function getDefaultPrice()
	{
		return $this->defaultPrice;
	}
		
	/**
	 * @param \Extras\Types\Price
	 * @return \Entity\Invoicing\Service
	 */
	public function setCurrentPrice(\Extras\Types\Price $currentPrice)
	{
		$this->currentPrice = $currentPrice;

		return $this;
	}
		
	/**
	 * @return \Extras\Types\Price|NULL
	 */
	public function getCurrentPrice()
	{
		return $this->currentPrice;
	}
		
	/**
	 * @param \Entity\Invoicing\Package
	 * @return \Entity\Invoicing\Service
	 */
	public function setPackage(\Entity\Invoicing\Package $package)
	{
		$this->package = $package;

		return $this;
	}
		
	/**
	 * @return \Entity\Invoicing\Service
	 */
	public function unsetPackage()
	{
		$this->package = NULL;

		return $this;
	}
		
	/**
	 * @return \Entity\Invoicing\Package|NULL
	 */
	public function getPackage()
	{
		return $this->package;
	}
}