<?php

namespace Entities\Invoicing;

use Entities\Attraction;
use Entities\Invoicing;
use Doctrine\Common\Collections\Collection
use Doctrine\Common\Collections\ArrayCollection


/**
 * @Entity()
 * @Table(name="invoicing_item")
 */
class Item extends \BaseEntity {

	/**
	 * @var Collection
	 * @Column(type="Invoice")
	 */
	protected $invoice;

	/**
	 * @var Collection
	 * @ManyToMany(targetEntity="Attraction\Type")
	 */
	protected $serviceType;

	/**
	 * @var string
	 * @Column(type="string", nullable=true)
	 */
	protected $name;

	/**
	 * @var string
	 * @Column(type="string", nullable=true)
	 */
	protected $nameEn;

	/**
	 * @var datetime
	 * @Column(type="datetime")
	 */
	protected $serviceFrom;

	/**
	 * @var datetime
	 * @Column(type="datetime")
	 */
	protected $serviceTo;

	/**
	 * @var string
	 * @Column(type="string", nullable=true)
	 */
	protected $durationName;

	/**
	 * @var string
	 * @Column(type="string", nullable=true)
	 */
	protected $durationNameEn;

	/**
	 * @var price
	 * @Column(type="price")
	 */
	protected $price;

	/**
	 * @var price
	 * @Column(type="price")
	 */
	protected $priceEur;

	/**
	 * @var string
	 * @Column(type="string", nullable=true)
	 */
	protected $marketingName;

	/**
	 * @var string
	 * @Column(type="string", nullable=true)
	 */
	protected $marketingNameEn;

	/**
	 * @var string
	 * @Column(type="string", nullable=true)
	 */
	protected $couponName;

	/**
	 * @var string
	 * @Column(type="string", nullable=true)
	 */
	protected $couponNameEn;

	/**
	 * @var string
	 * @Column(type="string", nullable=true)
	 */
	protected $packageName;

	/**
	 * @var string
	 * @Column(type="string", nullable=true)
	 */
	protected $packageNameEn;


	public function __construct() {

	}


	/**
	 * @param Invoice $invoice
	 * @return Item
	 */
	public function setInvoice(Invoice  $invoice) {
		$this->invoice = $invoice;
		return $this;
	}


	/**
	 * @return Invoice
	 */
	public function getInvoice() {
		return $this->invoice;
	}


	/**
	 * @param Attraction\Type $serviceType
	 * @return Item
	 */
	public function setServiceType(Attraction\Type  $serviceType) {
		$this->serviceType = $serviceType;
		return $this;
	}


	/**
	 * @return Attraction\Type
	 */
	public function getServiceType() {
		return $this->serviceType;
	}


	/**
	 * @param string $name
	 * @return Item
	 */
	public function setName($name) {
		$this->name = $name;
		return $this;
	}


	/**
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}


	/**
	 * @param string $nameEn
	 * @return Item
	 */
	public function setNameEn($nameEn) {
		$this->nameEn = $nameEn;
		return $this;
	}


	/**
	 * @return string
	 */
	public function getNameEn() {
		return $this->nameEn;
	}


	/**
	 * @param datetime $serviceFrom
	 * @return Item
	 */
	public function setServiceFrom($serviceFrom) {
		$this->serviceFrom = $serviceFrom;
		return $this;
	}


	/**
	 * @return datetime
	 */
	public function getServiceFrom() {
		return $this->serviceFrom;
	}


	/**
	 * @param datetime $serviceTo
	 * @return Item
	 */
	public function setServiceTo($serviceTo) {
		$this->serviceTo = $serviceTo;
		return $this;
	}


	/**
	 * @return datetime
	 */
	public function getServiceTo() {
		return $this->serviceTo;
	}


	/**
	 * @param string $durationName
	 * @return Item
	 */
	public function setDurationName($durationName) {
		$this->durationName = $durationName;
		return $this;
	}


	/**
	 * @return string
	 */
	public function getDurationName() {
		return $this->durationName;
	}


	/**
	 * @param string $durationNameEn
	 * @return Item
	 */
	public function setDurationNameEn($durationNameEn) {
		$this->durationNameEn = $durationNameEn;
		return $this;
	}


	/**
	 * @return string
	 */
	public function getDurationNameEn() {
		return $this->durationNameEn;
	}


	/**
	 * @param price $price
	 * @return Item
	 */
	public function setPrice($price) {
		$this->price = $price;
		return $this;
	}


	/**
	 * @return price
	 */
	public function getPrice() {
		return $this->price;
	}


	/**
	 * @param price $priceEur
	 * @return Item
	 */
	public function setPriceEur($priceEur) {
		$this->priceEur = $priceEur;
		return $this;
	}


	/**
	 * @return price
	 */
	public function getPriceEur() {
		return $this->priceEur;
	}


	/**
	 * @param string $marketingName
	 * @return Item
	 */
	public function setMarketingName($marketingName) {
		$this->marketingName = $marketingName;
		return $this;
	}


	/**
	 * @return string
	 */
	public function getMarketingName() {
		return $this->marketingName;
	}


	/**
	 * @param string $marketingNameEn
	 * @return Item
	 */
	public function setMarketingNameEn($marketingNameEn) {
		$this->marketingNameEn = $marketingNameEn;
		return $this;
	}


	/**
	 * @return string
	 */
	public function getMarketingNameEn() {
		return $this->marketingNameEn;
	}


	/**
	 * @param string $couponName
	 * @return Item
	 */
	public function setCouponName($couponName) {
		$this->couponName = $couponName;
		return $this;
	}


	/**
	 * @return string
	 */
	public function getCouponName() {
		return $this->couponName;
	}


	/**
	 * @param string $couponNameEn
	 * @return Item
	 */
	public function setCouponNameEn($couponNameEn) {
		$this->couponNameEn = $couponNameEn;
		return $this;
	}


	/**
	 * @return string
	 */
	public function getCouponNameEn() {
		return $this->couponNameEn;
	}


	/**
	 * @param string $packageName
	 * @return Item
	 */
	public function setPackageName($packageName) {
		$this->packageName = $packageName;
		return $this;
	}


	/**
	 * @return string
	 */
	public function getPackageName() {
		return $this->packageName;
	}


	/**
	 * @param string $packageNameEn
	 * @return Item
	 */
	public function setPackageNameEn($packageNameEn) {
		$this->packageNameEn = $packageNameEn;
		return $this;
	}


	/**
	 * @return string
	 */
	public function getPackageNameEn() {
		return $this->packageNameEn;
	}

}
