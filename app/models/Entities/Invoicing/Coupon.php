<?php

namespace Entities\Invoicing;

use Entities\Invoicing;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;


/**
 * @ORM\Entity()
 * @ORM\Table(name="invoicing_coupon")
 */
class Coupon extends \BaseEntity {

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
	 * @ORM\Column(type="Package")
	 */
	protected $recommenderPackage;

	/**
	 * @var Collection
	 * @ORM\Column(type="Package")
	 */
	protected $recommendeePackage;


	public function __construct() {

	}


	/**
	 * @param string $name
	 * @return Coupon
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
	 * @param string $code
	 * @return Coupon
	 */
	public function setCode($code) {
		$this->code = $code;
		return $this;
	}


	/**
	 * @return string
	 */
	public function getCode() {
		return $this->code;
	}


	/**
	 * @param integer $countTotal
	 * @return Coupon
	 */
	public function setCountTotal($countTotal) {
		$this->countTotal = $countTotal;
		return $this;
	}


	/**
	 * @return integer
	 */
	public function getCountTotal() {
		return $this->countTotal;
	}


	/**
	 * @param integer $countLeft
	 * @return Coupon
	 */
	public function setCountLeft($countLeft) {
		$this->countLeft = $countLeft;
		return $this;
	}


	/**
	 * @return integer
	 */
	public function getCountLeft() {
		return $this->countLeft;
	}


	/**
	 * @param datetime $validFrom
	 * @return Coupon
	 */
	public function setValidFrom($validFrom) {
		$this->validFrom = $validFrom;
		return $this;
	}


	/**
	 * @return datetime
	 */
	public function getValidFrom() {
		return $this->validFrom;
	}


	/**
	 * @param datetime $validTo
	 * @return Coupon
	 */
	public function setValidTo($validTo) {
		$this->validTo = $validTo;
		return $this;
	}


	/**
	 * @return datetime
	 */
	public function getValidTo() {
		return $this->validTo;
	}


	/**
	 * @param Package $recommenderPackage
	 * @return Coupon
	 */
	public function setRecommenderPackage(Package  $recommenderPackage) {
		$this->recommenderPackage = $recommenderPackage;
		return $this;
	}


	/**
	 * @return Package
	 */
	public function getRecommenderPackage() {
		return $this->recommenderPackage;
	}


	/**
	 * @param Package $recommendeePackage
	 * @return Coupon
	 */
	public function setRecommendeePackage(Package  $recommendeePackage) {
		$this->recommendeePackage = $recommendeePackage;
		return $this;
	}


	/**
	 * @return Package
	 */
	public function getRecommendeePackage() {
		return $this->recommendeePackage;
	}

}
