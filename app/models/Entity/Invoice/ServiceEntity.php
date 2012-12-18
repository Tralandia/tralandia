<?php

namespace Entity\Invoice;

use Doctrine\ORM\Mapping as ORM;
use	Extras\Annotation as EA;

/**
 * @ORM\Entity()
 * @ORM\Table(name="invoice_service")
 * @EA\Primary(key="id", value="id")
 * @EA\Generator(skip="{getCurrentPrice,getDefaultPrice}")
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
	 * @ORM\Column(type="float")
	 */
	protected $defaultPrice;

	/**
	 * @var price
	 * @ORM\Column(type="float")
	 */
	protected $currentPrice;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entity\Invoice\Package", inversedBy="services")
	 */
	protected $package;

	/**
	 * @return \Extras\Types\Price
	 */
	public function getDefaultPrice()
	{
		return new \Extras\Types\Price($this->defaultPrice, $this->getCurrency());
	}

	/**
	 * @return \Extras\Types\Price
	 */
	public function getCurrentPrice()
	{
		return new \Extras\Types\Price($this->currentPrice, $this->getCurrency());
	}

	public function getCurrency() {
		return $this->package->currency;
	}

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
	 * @param float
	 * @return \Entity\Invoice\Service
	 */
	public function setDefaultPrice($defaultPrice)
	{
		$this->defaultPrice = $defaultPrice;

		return $this;
	}
		
	/**
	 * @param float
	 * @return \Entity\Invoice\Service
	 */
	public function setCurrentPrice($currentPrice)
	{
		$this->currentPrice = $currentPrice;

		return $this;
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