<?php

namespace Entity\Invoicing;

use Entity\Invoicing;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="invoicing_coupon")
 */
class Coupon extends \Entity\BaseEntity {

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $name;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $code;

	/**
	 * @var integer
	 * @ORM\Column(type="integer")
	 */
	protected $countTotal;

	/**
	 * @var integer
	 * @ORM\Column(type="integer")
	 */
	protected $countLeft;

	/**
	 * @var datetime
	 * @ORM\Column(type="datetime")
	 */
	protected $validFrom;

	/**
	 * @var datetime
	 * @ORM\Column(type="datetime")
	 */
	protected $validTo;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Package")
	 */
	protected $recommenderPackage;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Package")
	 */
	protected $recommendeePackage;

	



//@entity-generator-code <--- NEMAZAT !!!

	/* ----------------------------- Methods ----------------------------- */		
	public function __construct() {
		parent::__construct();
	}
		
	/**
	 * @param string
	 * @return \Entity\Invoicing\Coupon
	 */
	public function setName($name) {
		$this->name = $name;

		return $this;
	}
		
	/**
	 * @return \Entity\Invoicing\Coupon
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
	 * @return \Entity\Invoicing\Coupon
	 */
	public function setCode($code) {
		$this->code = $code;

		return $this;
	}
		
	/**
	 * @return \Entity\Invoicing\Coupon
	 */
	public function unsetCode() {
		$this->code = NULL;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getCode() {
		return $this->code;
	}
		
	/**
	 * @param integer
	 * @return \Entity\Invoicing\Coupon
	 */
	public function setCountTotal($countTotal) {
		$this->countTotal = $countTotal;

		return $this;
	}
		
	/**
	 * @return integer|NULL
	 */
	public function getCountTotal() {
		return $this->countTotal;
	}
		
	/**
	 * @param integer
	 * @return \Entity\Invoicing\Coupon
	 */
	public function setCountLeft($countLeft) {
		$this->countLeft = $countLeft;

		return $this;
	}
		
	/**
	 * @return integer|NULL
	 */
	public function getCountLeft() {
		return $this->countLeft;
	}
		
	/**
	 * @param \DateTime
	 * @return \Entity\Invoicing\Coupon
	 */
	public function setValidFrom(\DateTime $validFrom) {
		$this->validFrom = $validFrom;

		return $this;
	}
		
	/**
	 * @return \DateTime|NULL
	 */
	public function getValidFrom() {
		return $this->validFrom;
	}
		
	/**
	 * @param \DateTime
	 * @return \Entity\Invoicing\Coupon
	 */
	public function setValidTo(\DateTime $validTo) {
		$this->validTo = $validTo;

		return $this;
	}
		
	/**
	 * @return \DateTime|NULL
	 */
	public function getValidTo() {
		return $this->validTo;
	}
		
	/**
	 * @param \Entity\Invoicing\Package
	 * @return \Entity\Invoicing\Coupon
	 */
	public function setRecommenderPackage(\Entity\Invoicing\Package $recommenderPackage) {
		$this->recommenderPackage = $recommenderPackage;

		return $this;
	}
		
	/**
	 * @return \Entity\Invoicing\Coupon
	 */
	public function unsetRecommenderPackage() {
		$this->recommenderPackage = NULL;

		return $this;
	}
		
	/**
	 * @return \Entity\Invoicing\Package|NULL
	 */
	public function getRecommenderPackage() {
		return $this->recommenderPackage;
	}
		
	/**
	 * @param \Entity\Invoicing\Package
	 * @return \Entity\Invoicing\Coupon
	 */
	public function setRecommendeePackage(\Entity\Invoicing\Package $recommendeePackage) {
		$this->recommendeePackage = $recommendeePackage;

		return $this;
	}
		
	/**
	 * @return \Entity\Invoicing\Coupon
	 */
	public function unsetRecommendeePackage() {
		$this->recommendeePackage = NULL;

		return $this;
	}
		
	/**
	 * @return \Entity\Invoicing\Package|NULL
	 */
	public function getRecommendeePackage() {
		return $this->recommendeePackage;
	}
}