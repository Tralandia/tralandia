<?php

namespace Entity\Invoice;

use Entity\Attraction;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="invoice_item")
 */
class Item extends \Entity\BaseEntity {

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Invoice", inversedBy="items")
	 */
	protected $invoice;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entity\Invoice\ServiceType")
	 */
	protected $serviceType;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $name;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $nameEn;

	/**
	 * @var datetime
	 * @ORM\Column(type="datetime", nullable=true)
	 */
	protected $serviceFrom;

	/**
	 * @var datetime
	 * @ORM\Column(type="datetime", nullable=true)
	 */
	protected $serviceTo;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $durationName;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $durationNameEn;

	/**
	 * @var float
	 * @ORM\Column(type="float")
	 */
	protected $price;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $marketingName;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $marketingNameEn;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $couponName;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $couponNameEn;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $packageName;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $packageNameEn;

	




















									//@entity-generator-code --- NEMAZAT !!!

	/* ----------------------------- Methods ----------------------------- */		
	public function __construct()
	{
		parent::__construct();
	}
		
	/**
	 * @param \Entity\Invoice\Invoice
	 * @return \Entity\Invoice\Item
	 */
	public function setInvoice(\Entity\Invoice\Invoice $invoice)
	{
		$this->invoice = $invoice;

		return $this;
	}
		
	/**
	 * @return \Entity\Invoice\Item
	 */
	public function unsetInvoice()
	{
		$this->invoice = NULL;

		return $this;
	}
		
	/**
	 * @return \Entity\Invoice\Invoice|NULL
	 */
	public function getInvoice()
	{
		return $this->invoice;
	}
		
	/**
	 * @param \Entity\Invoice\ServiceType
	 * @return \Entity\Invoice\Item
	 */
	public function setServiceType(\Entity\Invoice\ServiceType $serviceType)
	{
		$this->serviceType = $serviceType;

		return $this;
	}
		
	/**
	 * @return \Entity\Invoice\Item
	 */
	public function unsetServiceType()
	{
		$this->serviceType = NULL;

		return $this;
	}
		
	/**
	 * @return \Entity\Invoice\ServiceType|NULL
	 */
	public function getServiceType()
	{
		return $this->serviceType;
	}
		
	/**
	 * @param string
	 * @return \Entity\Invoice\Item
	 */
	public function setName($name)
	{
		$this->name = $name;

		return $this;
	}
		
	/**
	 * @return \Entity\Invoice\Item
	 */
	public function unsetName()
	{
		$this->name = NULL;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getName()
	{
		return $this->name;
	}
		
	/**
	 * @param string
	 * @return \Entity\Invoice\Item
	 */
	public function setNameEn($nameEn)
	{
		$this->nameEn = $nameEn;

		return $this;
	}
		
	/**
	 * @return \Entity\Invoice\Item
	 */
	public function unsetNameEn()
	{
		$this->nameEn = NULL;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getNameEn()
	{
		return $this->nameEn;
	}
		
	/**
	 * @param \DateTime
	 * @return \Entity\Invoice\Item
	 */
	public function setServiceFrom(\DateTime $serviceFrom)
	{
		$this->serviceFrom = $serviceFrom;

		return $this;
	}
		
	/**
	 * @return \Entity\Invoice\Item
	 */
	public function unsetServiceFrom()
	{
		$this->serviceFrom = NULL;

		return $this;
	}
		
	/**
	 * @return \DateTime|NULL
	 */
	public function getServiceFrom()
	{
		return $this->serviceFrom;
	}
		
	/**
	 * @param \DateTime
	 * @return \Entity\Invoice\Item
	 */
	public function setServiceTo(\DateTime $serviceTo)
	{
		$this->serviceTo = $serviceTo;

		return $this;
	}
		
	/**
	 * @return \Entity\Invoice\Item
	 */
	public function unsetServiceTo()
	{
		$this->serviceTo = NULL;

		return $this;
	}
		
	/**
	 * @return \DateTime|NULL
	 */
	public function getServiceTo()
	{
		return $this->serviceTo;
	}
		
	/**
	 * @param string
	 * @return \Entity\Invoice\Item
	 */
	public function setDurationName($durationName)
	{
		$this->durationName = $durationName;

		return $this;
	}
		
	/**
	 * @return \Entity\Invoice\Item
	 */
	public function unsetDurationName()
	{
		$this->durationName = NULL;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getDurationName()
	{
		return $this->durationName;
	}
		
	/**
	 * @param string
	 * @return \Entity\Invoice\Item
	 */
	public function setDurationNameEn($durationNameEn)
	{
		$this->durationNameEn = $durationNameEn;

		return $this;
	}
		
	/**
	 * @return \Entity\Invoice\Item
	 */
	public function unsetDurationNameEn()
	{
		$this->durationNameEn = NULL;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getDurationNameEn()
	{
		return $this->durationNameEn;
	}
		
	/**
	 * @param float
	 * @return \Entity\Invoice\Item
	 */
	public function setPrice($price)
	{
		$this->price = $price;

		return $this;
	}
		
	/**
	 * @return float|NULL
	 */
	public function getPrice()
	{
		return $this->price;
	}
		
	/**
	 * @param string
	 * @return \Entity\Invoice\Item
	 */
	public function setMarketingName($marketingName)
	{
		$this->marketingName = $marketingName;

		return $this;
	}
		
	/**
	 * @return \Entity\Invoice\Item
	 */
	public function unsetMarketingName()
	{
		$this->marketingName = NULL;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getMarketingName()
	{
		return $this->marketingName;
	}
		
	/**
	 * @param string
	 * @return \Entity\Invoice\Item
	 */
	public function setMarketingNameEn($marketingNameEn)
	{
		$this->marketingNameEn = $marketingNameEn;

		return $this;
	}
		
	/**
	 * @return \Entity\Invoice\Item
	 */
	public function unsetMarketingNameEn()
	{
		$this->marketingNameEn = NULL;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getMarketingNameEn()
	{
		return $this->marketingNameEn;
	}
		
	/**
	 * @param string
	 * @return \Entity\Invoice\Item
	 */
	public function setCouponName($couponName)
	{
		$this->couponName = $couponName;

		return $this;
	}
		
	/**
	 * @return \Entity\Invoice\Item
	 */
	public function unsetCouponName()
	{
		$this->couponName = NULL;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getCouponName()
	{
		return $this->couponName;
	}
		
	/**
	 * @param string
	 * @return \Entity\Invoice\Item
	 */
	public function setCouponNameEn($couponNameEn)
	{
		$this->couponNameEn = $couponNameEn;

		return $this;
	}
		
	/**
	 * @return \Entity\Invoice\Item
	 */
	public function unsetCouponNameEn()
	{
		$this->couponNameEn = NULL;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getCouponNameEn()
	{
		return $this->couponNameEn;
	}
		
	/**
	 * @param string
	 * @return \Entity\Invoice\Item
	 */
	public function setPackageName($packageName)
	{
		$this->packageName = $packageName;

		return $this;
	}
		
	/**
	 * @return \Entity\Invoice\Item
	 */
	public function unsetPackageName()
	{
		$this->packageName = NULL;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getPackageName()
	{
		return $this->packageName;
	}
		
	/**
	 * @param string
	 * @return \Entity\Invoice\Item
	 */
	public function setPackageNameEn($packageNameEn)
	{
		$this->packageNameEn = $packageNameEn;

		return $this;
	}
		
	/**
	 * @return \Entity\Invoice\Item
	 */
	public function unsetPackageNameEn()
	{
		$this->packageNameEn = NULL;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getPackageNameEn()
	{
		return $this->packageNameEn;
	}
}