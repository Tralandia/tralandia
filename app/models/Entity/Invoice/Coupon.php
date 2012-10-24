<?php

namespace Entity\Invoice;

use Doctrine\ORM\Mapping as ORM;
use	Extras\Annotation as EA;

/**
 * @ORM\Entity()
 * @ORM\Table(name="invoice_coupon", indexes={@ORM\index(name="code", columns={"code"}), @ORM\index(name="countTotal", columns={"countTotal"}), @ORM\index(name="countLeft", columns={"countLeft"}), @ORM\index(name="validFrom", columns={"validFrom"}), @ORM\index(name="validTo", columns={"validTo"})})
 * @EA\Primary(key="id", value="name")
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


									//@entity-generator-code --- NEMAZAT !!!

	/* ----------------------------- Methods ----------------------------- */		
	public function __construct()
	{
		parent::__construct();
	}
		
	/**
	 * @param string
	 * @return \Entity\Invoice\Coupon
	 */
	public function setName($name)
	{
		$this->name = $name;

		return $this;
	}
		
	/**
	 * @return \Entity\Invoice\Coupon
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
	 * @return \Entity\Invoice\Coupon
	 */
	public function setCode($code)
	{
		$this->code = $code;

		return $this;
	}
		
	/**
	 * @return \Entity\Invoice\Coupon
	 */
	public function unsetCode()
	{
		$this->code = NULL;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getCode()
	{
		return $this->code;
	}
		
	/**
	 * @param integer
	 * @return \Entity\Invoice\Coupon
	 */
	public function setCountTotal($countTotal)
	{
		$this->countTotal = $countTotal;

		return $this;
	}
		
	/**
	 * @return integer|NULL
	 */
	public function getCountTotal()
	{
		return $this->countTotal;
	}
		
	/**
	 * @param integer
	 * @return \Entity\Invoice\Coupon
	 */
	public function setCountLeft($countLeft)
	{
		$this->countLeft = $countLeft;

		return $this;
	}
		
	/**
	 * @return integer|NULL
	 */
	public function getCountLeft()
	{
		return $this->countLeft;
	}
		
	/**
	 * @param \DateTime
	 * @return \Entity\Invoice\Coupon
	 */
	public function setValidFrom(\DateTime $validFrom)
	{
		$this->validFrom = $validFrom;

		return $this;
	}
		
	/**
	 * @return \DateTime|NULL
	 */
	public function getValidFrom()
	{
		return $this->validFrom;
	}
		
	/**
	 * @param \DateTime
	 * @return \Entity\Invoice\Coupon
	 */
	public function setValidTo(\DateTime $validTo)
	{
		$this->validTo = $validTo;

		return $this;
	}
		
	/**
	 * @return \DateTime|NULL
	 */
	public function getValidTo()
	{
		return $this->validTo;
	}
		
	/**
	 * @param \Entity\Invoice\Package
	 * @return \Entity\Invoice\Coupon
	 */
	public function setRecommenderPackage(\Entity\Invoice\Package $recommenderPackage)
	{
		$this->recommenderPackage = $recommenderPackage;

		return $this;
	}
		
	/**
	 * @return \Entity\Invoice\Coupon
	 */
	public function unsetRecommenderPackage()
	{
		$this->recommenderPackage = NULL;

		return $this;
	}
		
	/**
	 * @return \Entity\Invoice\Package|NULL
	 */
	public function getRecommenderPackage()
	{
		return $this->recommenderPackage;
	}
		
	/**
	 * @param \Entity\Invoice\Package
	 * @return \Entity\Invoice\Coupon
	 */
	public function setRecommendeePackage(\Entity\Invoice\Package $recommendeePackage)
	{
		$this->recommendeePackage = $recommendeePackage;

		return $this;
	}
		
	/**
	 * @return \Entity\Invoice\Coupon
	 */
	public function unsetRecommendeePackage()
	{
		$this->recommendeePackage = NULL;

		return $this;
	}
		
	/**
	 * @return \Entity\Invoice\Package|NULL
	 */
	public function getRecommendeePackage()
	{
		return $this->recommendeePackage;
	}
}