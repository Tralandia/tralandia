<?php

namespace Entity\Invoice;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Repository\Invoice\ItemRepository")
 * @ORM\Table(name="invoice_item")
 * @EA\Generator(skip="{getPrice, setPrice}")
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
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $duration;

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
	 * @var float
	 * @ORM\Column(type="float")
	 */
	protected $price;

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


	/**
	 * @return \Extras\Types\Price
	 */
	public function getPrice()
	{
		return new \Extras\Types\Price($this->price, $this->getCurrency());
	}

	/**
	 * @param \Extras\Types\Price $price
	 *
	 * @return Item
	 */
	public function setPrice(\Extras\Types\Price $price)
	{
		$this->price = $price->convertToFloat($this->getCurrency());
		return $this;
	}

	public function getCurrency() {
		return $this->invoice->currency;
	}


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
	 * @param string
	 * @return \Entity\Invoice\Item
	 */
	public function setDuration($duration)
	{
		$this->duration = $duration;

		return $this;
	}
		
	/**
	 * @return \Entity\Invoice\Item
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