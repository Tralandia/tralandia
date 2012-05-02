<?php

namespace Entity\Invoicing;

use Entity\Attraction;
use Entity\Invoicing;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="invoicing_item")
 */
class Item extends \Entity\BaseEntity {

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Invoice", inversedBy="items")
	 */
	protected $invoice;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entity\Invoicing\Service\Type")
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
	 * @ORM\Column(type="datetime")
	 */
	protected $serviceFrom;

	/**
	 * @var datetime
	 * @ORM\Column(type="datetime")
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

	















//@entity-generator-code <--- NEMAZAT !!!

	/* ----------------------------- Methods ----------------------------- */		
	public function __construct() {
		parent::__construct();
	}
		
	/**
	 * @param \Entity\Invoicing\Invoice
	 * @return \Entity\Invoicing\Item
	 */
	public function setInvoice(\Entity\Invoicing\Invoice $invoice) {
		$this->invoice = $invoice;

		return $this;
	}
		
	/**
	 * @return \Entity\Invoicing\Item
	 */
	public function unsetInvoice() {
		$this->invoice = NULL;

		return $this;
	}
		
	/**
	 * @return \Entity\Invoicing\Invoice|NULL
	 */
	public function getInvoice() {
		return $this->invoice;
	}
		
	/**
	 * @param \Entity\Invoicing\Service\Type
	 * @return \Entity\Invoicing\Item
	 */
	public function setServiceType(\Entity\Invoicing\Service\Type $serviceType) {
		$this->serviceType = $serviceType;

		return $this;
	}
		
	/**
	 * @return \Entity\Invoicing\Item
	 */
	public function unsetServiceType() {
		$this->serviceType = NULL;

		return $this;
	}
		
	/**
	 * @return \Entity\Invoicing\Service\Type|NULL
	 */
	public function getServiceType() {
		return $this->serviceType;
	}
		
	/**
	 * @param string
	 * @return \Entity\Invoicing\Item
	 */
	public function setName($name) {
		$this->name = $name;

		return $this;
	}
		
	/**
	 * @return \Entity\Invoicing\Item
	 */
	public function unsetName() {
		$this->name = NULL;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getName() {
		return $this->name;
	}
		
	/**
	 * @param string
	 * @return \Entity\Invoicing\Item
	 */
	public function setNameEn($nameEn) {
		$this->nameEn = $nameEn;

		return $this;
	}
		
	/**
	 * @return \Entity\Invoicing\Item
	 */
	public function unsetNameEn() {
		$this->nameEn = NULL;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getNameEn() {
		return $this->nameEn;
	}
		
	/**
	 * @param \DateTime
	 * @return \Entity\Invoicing\Item
	 */
	public function setServiceFrom(\DateTime $serviceFrom) {
		$this->serviceFrom = $serviceFrom;

		return $this;
	}
		
	/**
	 * @return \DateTime|NULL
	 */
	public function getServiceFrom() {
		return $this->serviceFrom;
	}
		
	/**
	 * @param \DateTime
	 * @return \Entity\Invoicing\Item
	 */
	public function setServiceTo(\DateTime $serviceTo) {
		$this->serviceTo = $serviceTo;

		return $this;
	}
		
	/**
	 * @return \DateTime|NULL
	 */
	public function getServiceTo() {
		return $this->serviceTo;
	}
		
	/**
	 * @param string
	 * @return \Entity\Invoicing\Item
	 */
	public function setDurationName($durationName) {
		$this->durationName = $durationName;

		return $this;
	}
		
	/**
	 * @return \Entity\Invoicing\Item
	 */
	public function unsetDurationName() {
		$this->durationName = NULL;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getDurationName() {
		return $this->durationName;
	}
		
	/**
	 * @param string
	 * @return \Entity\Invoicing\Item
	 */
	public function setDurationNameEn($durationNameEn) {
		$this->durationNameEn = $durationNameEn;

		return $this;
	}
		
	/**
	 * @return \Entity\Invoicing\Item
	 */
	public function unsetDurationNameEn() {
		$this->durationNameEn = NULL;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getDurationNameEn() {
		return $this->durationNameEn;
	}
		
	/**
	 * @param \Extras\Types\Price
	 * @return \Entity\Invoicing\Item
	 */
	public function setPrice(\Extras\Types\Price $price) {
		$this->price = $price;

		return $this;
	}
		
	/**
	 * @return \Extras\Types\Price|NULL
	 */
	public function getPrice() {
		return $this->price;
	}
		
	/**
	 * @param \Extras\Types\Price
	 * @return \Entity\Invoicing\Item
	 */
	public function setPriceEur(\Extras\Types\Price $priceEur) {
		$this->priceEur = $priceEur;

		return $this;
	}
		
	/**
	 * @return \Extras\Types\Price|NULL
	 */
	public function getPriceEur() {
		return $this->priceEur;
	}
		
	/**
	 * @param string
	 * @return \Entity\Invoicing\Item
	 */
	public function setMarketingName($marketingName) {
		$this->marketingName = $marketingName;

		return $this;
	}
		
	/**
	 * @return \Entity\Invoicing\Item
	 */
	public function unsetMarketingName() {
		$this->marketingName = NULL;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getMarketingName() {
		return $this->marketingName;
	}
		
	/**
	 * @param string
	 * @return \Entity\Invoicing\Item
	 */
	public function setMarketingNameEn($marketingNameEn) {
		$this->marketingNameEn = $marketingNameEn;

		return $this;
	}
		
	/**
	 * @return \Entity\Invoicing\Item
	 */
	public function unsetMarketingNameEn() {
		$this->marketingNameEn = NULL;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getMarketingNameEn() {
		return $this->marketingNameEn;
	}
		
	/**
	 * @param string
	 * @return \Entity\Invoicing\Item
	 */
	public function setCouponName($couponName) {
		$this->couponName = $couponName;

		return $this;
	}
		
	/**
	 * @return \Entity\Invoicing\Item
	 */
	public function unsetCouponName() {
		$this->couponName = NULL;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getCouponName() {
		return $this->couponName;
	}
		
	/**
	 * @param string
	 * @return \Entity\Invoicing\Item
	 */
	public function setCouponNameEn($couponNameEn) {
		$this->couponNameEn = $couponNameEn;

		return $this;
	}
		
	/**
	 * @return \Entity\Invoicing\Item
	 */
	public function unsetCouponNameEn() {
		$this->couponNameEn = NULL;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getCouponNameEn() {
		return $this->couponNameEn;
	}
		
	/**
	 * @param string
	 * @return \Entity\Invoicing\Item
	 */
	public function setPackageName($packageName) {
		$this->packageName = $packageName;

		return $this;
	}
		
	/**
	 * @return \Entity\Invoicing\Item
	 */
	public function unsetPackageName() {
		$this->packageName = NULL;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getPackageName() {
		return $this->packageName;
	}
		
	/**
	 * @param string
	 * @return \Entity\Invoicing\Item
	 */
	public function setPackageNameEn($packageNameEn) {
		$this->packageNameEn = $packageNameEn;

		return $this;
	}
		
	/**
	 * @return \Entity\Invoicing\Item
	 */
	public function unsetPackageNameEn() {
		$this->packageNameEn = NULL;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getPackageNameEn() {
		return $this->packageNameEn;
	}
}