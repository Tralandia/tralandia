<?php

namespace Entity\Invoice;

use Doctrine\ORM\Mapping as ORM;
use	Extras\Annotation as EA;

/**
 * @ORM\Entity()
 * @ORM\Table(name="invoice_service")
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
	 * @ORM\ManyToOne(targetEntity="Entity\Invoice\Package", inversedBy="services")
	 */
	protected $package;
	




















	//@entity-generator-code --- NEMAZAT !!!

	/* ----------------------------- Methods ----------------------------- */		
	public function __construct()
	{
		parent::__construct();
	}
		
	/**
	 * @param \Entity\Invoice\ServiceType
	 * @return \Entity\Invoice\Service
	 */
	public function setType(\Entity\Invoice\ServiceType $type)
	{
		$this->type = $type;

		return $this;
	}
		
	/**
	 * @return \Entity\Invoice\Service
	 */
	public function unsetType()
	{
		$this->type = NULL;

		return $this;
	}
		
	/**
	 * @return \Entity\Invoice\ServiceType|NULL
	 */
	public function getType()
	{
		return $this->type;
	}
		
	/**
	 * @param \Entity\Invoice\ServiceDuration
	 * @return \Entity\Invoice\Service
	 */
	public function setDuration(\Entity\Invoice\ServiceDuration $duration)
	{
		$this->duration = $duration;

		return $this;
	}
		
	/**
	 * @return \Entity\Invoice\Service
	 */
	public function unsetDuration()
	{
		$this->duration = NULL;

		return $this;
	}
		
	/**
	 * @return \Entity\Invoice\ServiceDuration|NULL
	 */
	public function getDuration()
	{
		return $this->duration;
	}
		
	/**
	 * @param \Extras\Types\Price
	 * @return \Entity\Invoice\Service
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
	 * @return \Entity\Invoice\Service
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
	 * @param \Entity\Invoice\Package
	 * @return \Entity\Invoice\Service
	 */
	public function setPackage(\Entity\Invoice\Package $package)
	{
		$this->package = $package;

		return $this;
	}
		
	/**
	 * @return \Entity\Invoice\Service
	 */
	public function unsetPackage()
	{
		$this->package = NULL;

		return $this;
	}
		
	/**
	 * @return \Entity\Invoice\Package|NULL
	 */
	public function getPackage()
	{
		return $this->package;
	}
}