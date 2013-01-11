<?php

namespace Entity\Invoice;

use Entity\Phrase;
use Entity\Rental;
use Doctrine\ORM\Mapping as ORM;
use	Extras\Annotation as EA;

/**
 * @ORM\Entity(repositoryClass="Repository\Invoice\InvoiceRepository")
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="invoice", indexes={@ORM\index(name="invoiceNumber", columns={"invoiceNumber"}), @ORM\index(name="due", columns={"due"}), @ORM\index(name="paid", columns={"paid"})})
 * @EA\Generator(skip="{addItem, removeItem, setPrice}")
 */
class Invoice extends \Entity\BaseEntity {

	/**
	 * @var Collection
	 * @ORM\OneToMany(targetEntity="Item", mappedBy="invoice", cascade={"persist", "remove"})
	 */
	protected $items;

	/**
	 * @var integer
	 * @ORM\Column(type="integer", nullable=true)
	 */
	protected $invoiceNumber;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entity\Rental\Rental", inversedBy="invoices")
	 */
	protected $rental;

	/**
	 * @var datetime
	 * @ORM\Column(type="datetime")
	 */
	protected $due;

	/**
	 * @var datetime
	 * @ORM\Column(type="datetime", nullable=true)
	 */
	protected $paid;

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="InvoicingData", cascade={"persist", "remove"})
	 */
	protected $clientInvoicingData;

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="InvoicingData", cascade={"persist", "remove"})
	 */
	protected $ourInvoicingData;

	/**
	 * @var float
	 * @ORM\Column(type="float", nullable=true)
	 */
	protected $vat;

	/**
	 * @var float
	 * @ORM\Column(type="float")
	 */
	protected $price;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entity\Currency")
	 */
	protected $currency;

	/**
	 * @var float
	 * @ORM\Column(type="float", nullable=true)
	 */
	protected $exchangeRate;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $createdBy;

	/**
	 * @var json
	 * @ORM\Column(type="json")
	 */
	protected $paymentInfo;

	/**
	 * @var Collection
	 * @ORM\OneToMany(targetEntity="Entity\Rental\Referral", mappedBy="invoice", cascade={"persist"})
	 */
	protected $referrals;


	/**
	 * @param \Entity\Invoice\Item
	 * @return \Entity\Invoice\Invoice
	 */
	public function addItem(\Entity\Invoice\Item $item)
	{
		if(!$this->items->contains($item)) {
			$this->items->add($item);
		}
		$item->setInvoice($this);

		return $this;
	}
		
	/**
	 * @param \Entity\Invoice\Item
	 * @return \Entity\Invoice\Invoice
	 */
	public function removeItem(\Entity\Invoice\Item $item)
	{
		if($this->items->contains($item)) {
			$this->items->removeElement($item);
		}
		$item->unsetInvoice();

		return $this;
	}

	/**
	 * @ORM\prePersist
	 * @ORM\preUpdate
	 * @return Invoice
	 */
	public function updatePrice()
	{
		$price = 0.0;
		foreach ($this->getItems() as $item) {
			$price += $item->price;
		}
		$this->price = $price;
		return $this;
	}


	//@entity-generator-code --- NEMAZAT !!!

	/* ----------------------------- Methods ----------------------------- */		
	public function __construct()
	{
		parent::__construct();

		$this->items = new \Doctrine\Common\Collections\ArrayCollection;
		$this->referrals = new \Doctrine\Common\Collections\ArrayCollection;
	}
		
	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection|\Entity\Invoice\Item[]
	 */
	public function getItems()
	{
		return $this->items;
	}
		
	/**
	 * @param integer
	 * @return \Entity\Invoice\Invoice
	 */
	public function setInvoiceNumber($invoiceNumber)
	{
		$this->invoiceNumber = $invoiceNumber;

		return $this;
	}
		
	/**
	 * @return \Entity\Invoice\Invoice
	 */
	public function unsetInvoiceNumber()
	{
		$this->invoiceNumber = NULL;

		return $this;
	}
		
	/**
	 * @return integer|NULL
	 */
	public function getInvoiceNumber()
	{
		return $this->invoiceNumber;
	}
		
	/**
	 * @param \Entity\Rental\Rental
	 * @return \Entity\Invoice\Invoice
	 */
	public function setRental(\Entity\Rental\Rental $rental)
	{
		$this->rental = $rental;

		return $this;
	}
		
	/**
	 * @return \Entity\Invoice\Invoice
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
	 * @param \DateTime
	 * @return \Entity\Invoice\Invoice
	 */
	public function setDue(\DateTime $due)
	{
		$this->due = $due;

		return $this;
	}
		
	/**
	 * @return \DateTime|NULL
	 */
	public function getDue()
	{
		return $this->due;
	}
		
	/**
	 * @param \DateTime
	 * @return \Entity\Invoice\Invoice
	 */
	public function setPaid(\DateTime $paid)
	{
		$this->paid = $paid;

		return $this;
	}
		
	/**
	 * @return \Entity\Invoice\Invoice
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
	 * @param \Entity\Invoice\InvoicingData
	 * @return \Entity\Invoice\Invoice
	 */
	public function setClientInvoicingData(\Entity\Invoice\InvoicingData $clientInvoicingData)
	{
		$this->clientInvoicingData = $clientInvoicingData;

		return $this;
	}
		
	/**
	 * @return \Entity\Invoice\InvoicingData|NULL
	 */
	public function getClientInvoicingData()
	{
		return $this->clientInvoicingData;
	}
		
	/**
	 * @param \Entity\Invoice\InvoicingData
	 * @return \Entity\Invoice\Invoice
	 */
	public function setOurInvoicingData(\Entity\Invoice\InvoicingData $ourInvoicingData)
	{
		$this->ourInvoicingData = $ourInvoicingData;

		return $this;
	}
		
	/**
	 * @return \Entity\Invoice\InvoicingData|NULL
	 */
	public function getOurInvoicingData()
	{
		return $this->ourInvoicingData;
	}
		
	/**
	 * @param float
	 * @return \Entity\Invoice\Invoice
	 */
	public function setVat($vat)
	{
		$this->vat = $vat;

		return $this;
	}
		
	/**
	 * @return \Entity\Invoice\Invoice
	 */
	public function unsetVat()
	{
		$this->vat = NULL;

		return $this;
	}
		
	/**
	 * @return float|NULL
	 */
	public function getVat()
	{
		return $this->vat;
	}
		
	/**
	 * @return float|NULL
	 */
	public function getPrice()
	{
		return $this->price;
	}
		
	/**
	 * @param \Entity\Currency
	 * @return \Entity\Invoice\Invoice
	 */
	public function setCurrency(\Entity\Currency $currency)
	{
		$this->currency = $currency;

		return $this;
	}
		
	/**
	 * @return \Entity\Invoice\Invoice
	 */
	public function unsetCurrency()
	{
		$this->currency = NULL;

		return $this;
	}
		
	/**
	 * @return \Entity\Currency|NULL
	 */
	public function getCurrency()
	{
		return $this->currency;
	}
		
	/**
	 * @param float
	 * @return \Entity\Invoice\Invoice
	 */
	public function setExchangeRate($exchangeRate)
	{
		$this->exchangeRate = $exchangeRate;

		return $this;
	}
		
	/**
	 * @return \Entity\Invoice\Invoice
	 */
	public function unsetExchangeRate()
	{
		$this->exchangeRate = NULL;

		return $this;
	}
		
	/**
	 * @return float|NULL
	 */
	public function getExchangeRate()
	{
		return $this->exchangeRate;
	}
		
	/**
	 * @param string
	 * @return \Entity\Invoice\Invoice
	 */
	public function setCreatedBy($createdBy)
	{
		$this->createdBy = $createdBy;

		return $this;
	}
		
	/**
	 * @return \Entity\Invoice\Invoice
	 */
	public function unsetCreatedBy()
	{
		$this->createdBy = NULL;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getCreatedBy()
	{
		return $this->createdBy;
	}
		
	/**
	 * @param json
	 * @return \Entity\Invoice\Invoice
	 */
	public function setPaymentInfo($paymentInfo)
	{
		$this->paymentInfo = $paymentInfo;

		return $this;
	}
		
	/**
	 * @return json|NULL
	 */
	public function getPaymentInfo()
	{
		return $this->paymentInfo;
	}
		
	/**
	 * @param \Entity\Rental\Referral
	 * @return \Entity\Invoice\Invoice
	 */
	public function addReferral(\Entity\Rental\Referral $referral)
	{
		if(!$this->referrals->contains($referral)) {
			$this->referrals->add($referral);
		}
		$referral->setInvoice($this);

		return $this;
	}
		
	/**
	 * @param \Entity\Rental\Referral
	 * @return \Entity\Invoice\Invoice
	 */
	public function removeReferral(\Entity\Rental\Referral $referral)
	{
		$this->referrals->removeElement($referral);
		$referral->unsetInvoice();

		return $this;
	}
		
	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection|\Entity\Rental\Referral[]
	 */
	public function getReferrals()
	{
		return $this->referrals;
	}
}