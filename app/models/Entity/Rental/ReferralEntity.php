<?php

namespace Entity\Rental;

use Entity\Phrase;
use Doctrine\ORM\Mapping as ORM;
use	Extras\Annotation as EA;

/**
 * @ORM\Entity()
 * @ORM\Table(name="rental_referral")
 * @EA\Primary(key="id", value="id")
 * @EA\Generator(skip="{getPrice}")
 */
class Referral extends \Entity\BaseEntity {

	/**
	 * @var string
	 * @ORM\Column(type="string")
	 */
	protected $referrer;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Rental", inversedBy="referrals")
	 */
	protected $rental;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entity\Invoice\Invoice", inversedBy="referrals")
	 */
	protected $invoice;

	/**
	 * @var datetime
	 * @ORM\Column(type="datetime", nullable=true)
	 */
	protected $paid;

	/**
	 * @var float
	 * @ORM\Column(type="float")
	 */
	protected $price;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entity\Currency")
	 */
	protected $priceCurrency;


	/**
	 * @return \Extras\Types\Price
	 */
	public function getPrice()
	{
		return new \Extras\Types\Price($this->price, $this->getPriceCurrency());
	}


	//@entity-generator-code --- NEMAZAT !!!

	/* ----------------------------- Methods ----------------------------- */		
	public function __construct()
	{
		parent::__construct();
	}
		
	/**
	 * @param string
	 * @return \Entity\Rental\Referral
	 */
	public function setReferrer($referrer)
	{
		$this->referrer = $referrer;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getReferrer()
	{
		return $this->referrer;
	}
		
	/**
	 * @param \Entity\Rental\Rental
	 * @return \Entity\Rental\Referral
	 */
	public function setRental(\Entity\Rental\Rental $rental)
	{
		$this->rental = $rental;

		return $this;
	}
		
	/**
	 * @return \Entity\Rental\Referral
	 */
	public function unsetRental()
	{
		$this->rental = NULL;

		return $this;
	}
		
	/**
	 * @return \Entity\Rental\Rental|NULL
	 */
	public function getRental()
	{
		return $this->rental;
	}
		
	/**
	 * @param \Entity\Invoice\Invoice
	 * @return \Entity\Rental\Referral
	 */
	public function setInvoice(\Entity\Invoice\Invoice $invoice)
	{
		$this->invoice = $invoice;

		return $this;
	}
		
	/**
	 * @return \Entity\Rental\Referral
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
	 * @param \DateTime
	 * @return \Entity\Rental\Referral
	 */
	public function setPaid(\DateTime $paid)
	{
		$this->paid = $paid;

		return $this;
	}
		
	/**
	 * @return \Entity\Rental\Referral
	 */
	public function unsetPaid()
	{
		$this->paid = NULL;

		return $this;
	}
		
	/**
	 * @return \DateTime|NULL
	 */
	public function getPaid()
	{
		return $this->paid;
	}
		
	/**
	 * @param float
	 * @return \Entity\Rental\Referral
	 */
	public function setPrice($price)
	{
		$this->price = $price;

		return $this;
	}
				
	/**
	 * @param \Entity\Currency
	 * @return \Entity\Rental\Referral
	 */
	public function setPriceCurrency(\Entity\Currency $priceCurrency)
	{
		$this->priceCurrency = $priceCurrency;

		return $this;
	}
		
	/**
	 * @return \Entity\Rental\Referral
	 */
	public function unsetPriceCurrency()
	{
		$this->priceCurrency = NULL;

		return $this;
	}
		
	/**
	 * @return \Entity\Currency|NULL
	 */
	public function getPriceCurrency()
	{
		return $this->priceCurrency;
	}
}