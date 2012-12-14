<?php

namespace Entity\Invoice;

use Entity\Company;
use Entity\Phrase;
use Entity\Rental;
use Doctrine\ORM\Mapping as ORM;
use	Extras\Annotation as EA;

/**
 * @ORM\Entity()
 * @ORM\Table(name="invoice", indexes={@ORM\index(name="invoiceNumber", columns={"invoiceNumber"}), @ORM\index(name="paymentReferenceNumber", columns={"paymentReferenceNumber"}), @ORM\index(name="due", columns={"due"}), @ORM\index(name="paid", columns={"paid"}), @ORM\index(name="status", columns={"status"}), @ORM\index(name="clientEmail", columns={"clientEmail"}), @ORM\index(name="referrer", columns={"referrer"}), @ORM\index(name="referrerCommission", columns={"referrerCommission"})})
 * @EA\Primary(key="id", value="clientName")
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
	 * @ORM\ManyToOne(targetEntity="Entity\Company\Company", inversedBy="invoices")
	 */
	protected $company;

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
	 * @ORM\Column(type="datetime")
	 */
	protected $paid;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $clientName;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $clientPhone;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $clientEmail;

	/**
	 * @var url
	 * @ORM\Column(type="url", nullable=true)
	 */
	protected $clientUrl;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $clientAddress;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $clientLocality;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $clientPostalCode;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entity\Location\Location")
	 */
	protected $clientPrimaryLocation;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entity\Language")
	 */
	protected $clientLanguage;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $clientCompanyName;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $clientCompanyId;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $clientCompanyVatId;

	/**
	 * @var float
	 * @ORM\Column(type="float", nullable=true)
	 */
	protected $vat;

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

	//@entity-generator-code --- NEMAZAT !!!

	/* ----------------------------- Methods ----------------------------- */		
	public function __construct()
	{
		parent::__construct();

		$this->items = new \Doctrine\Common\Collections\ArrayCollection;
	}
		
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
	 * @return \Doctrine\Common\Collections\ArrayCollection of \Entity\Invoice\Item
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
	 * @param \Entity\Company\Company
	 * @return \Entity\Invoice\Invoice
	 */
	public function setCompany(\Entity\Company\Company $company)
	{
		$this->company = $company;

		return $this;
	}
		
	/**
	 * @return \Entity\Invoice\Invoice
	 */
	public function unsetCompany()
	{
		$this->company = NULL;

		return $this;
	}
		
	/**
	 * @return \Entity\Company\Company|NULL
	 */
	public function getCompany()
	{
		return $this->company;
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
	 * @return \DateTime|NULL
	 */
	public function getPaid()
	{
		return $this->paid;
	}
		
	/**
	 * @param string
	 * @return \Entity\Invoice\Invoice
	 */
	public function setClientName($clientName)
	{
		$this->clientName = $clientName;

		return $this;
	}
		
	/**
	 * @return \Entity\Invoice\Invoice
	 */
	public function unsetClientName()
	{
		$this->clientName = NULL;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getClientName()
	{
		return $this->clientName;
	}
		
	/**
	 * @param string
	 * @return \Entity\Invoice\Invoice
	 */
	public function setClientPhone($clientPhone)
	{
		$this->clientPhone = $clientPhone;

		return $this;
	}
		
	/**
	 * @return \Entity\Invoice\Invoice
	 */
	public function unsetClientPhone()
	{
		$this->clientPhone = NULL;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getClientPhone()
	{
		return $this->clientPhone;
	}
		
	/**
	 * @param string
	 * @return \Entity\Invoice\Invoice
	 */
	public function setClientEmail($clientEmail)
	{
		$this->clientEmail = $clientEmail;

		return $this;
	}
		
	/**
	 * @return \Entity\Invoice\Invoice
	 */
	public function unsetClientEmail()
	{
		$this->clientEmail = NULL;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getClientEmail()
	{
		return $this->clientEmail;
	}
		
	/**
	 * @param \Extras\Types\Url
	 * @return \Entity\Invoice\Invoice
	 */
	public function setClientUrl(\Extras\Types\Url $clientUrl)
	{
		$this->clientUrl = $clientUrl;

		return $this;
	}
		
	/**
	 * @return \Entity\Invoice\Invoice
	 */
	public function unsetClientUrl()
	{
		$this->clientUrl = NULL;

		return $this;
	}
		
	/**
	 * @return \Extras\Types\Url|NULL
	 */
	public function getClientUrl()
	{
		return $this->clientUrl;
	}
		
	/**
	 * @param string
	 * @return \Entity\Invoice\Invoice
	 */
	public function setClientAddress($clientAddress)
	{
		$this->clientAddress = $clientAddress;

		return $this;
	}
		
	/**
	 * @return \Entity\Invoice\Invoice
	 */
	public function unsetClientAddress()
	{
		$this->clientAddress = NULL;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getClientAddress()
	{
		return $this->clientAddress;
	}
		
	/**
	 * @param string
	 * @return \Entity\Invoice\Invoice
	 */
	public function setClientLocality($clientLocality)
	{
		$this->clientLocality = $clientLocality;

		return $this;
	}
		
	/**
	 * @return \Entity\Invoice\Invoice
	 */
	public function unsetClientLocality()
	{
		$this->clientLocality = NULL;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getClientLocality()
	{
		return $this->clientLocality;
	}
		
	/**
	 * @param string
	 * @return \Entity\Invoice\Invoice
	 */
	public function setClientPostalCode($clientPostalCode)
	{
		$this->clientPostalCode = $clientPostalCode;

		return $this;
	}
		
	/**
	 * @return \Entity\Invoice\Invoice
	 */
	public function unsetClientPostalCode()
	{
		$this->clientPostalCode = NULL;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getClientPostalCode()
	{
		return $this->clientPostalCode;
	}
		
	/**
	 * @param \Entity\Location\Location
	 * @return \Entity\Invoice\Invoice
	 */
	public function setClientPrimaryLocation(\Entity\Location\Location $clientPrimaryLocation)
	{
		$this->clientPrimaryLocation = $clientPrimaryLocation;

		return $this;
	}
		
	/**
	 * @return \Entity\Invoice\Invoice
	 */
	public function unsetClientPrimaryLocation()
	{
		$this->clientPrimaryLocation = NULL;

		return $this;
	}
		
	/**
	 * @return \Entity\Location\Location|NULL
	 */
	public function getClientPrimaryLocation()
	{
		return $this->clientPrimaryLocation;
	}
		
	/**
	 * @param \Entity\Language
	 * @return \Entity\Invoice\Invoice
	 */
	public function setClientLanguage(\Entity\Language $clientLanguage)
	{
		$this->clientLanguage = $clientLanguage;

		return $this;
	}
		
	/**
	 * @return \Entity\Invoice\Invoice
	 */
	public function unsetClientLanguage()
	{
		$this->clientLanguage = NULL;

		return $this;
	}
		
	/**
	 * @return \Entity\Language|NULL
	 */
	public function getClientLanguage()
	{
		return $this->clientLanguage;
	}
		
	/**
	 * @param string
	 * @return \Entity\Invoice\Invoice
	 */
	public function setClientCompanyName($clientCompanyName)
	{
		$this->clientCompanyName = $clientCompanyName;

		return $this;
	}
		
	/**
	 * @return \Entity\Invoice\Invoice
	 */
	public function unsetClientCompanyName()
	{
		$this->clientCompanyName = NULL;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getClientCompanyName()
	{
		return $this->clientCompanyName;
	}
		
	/**
	 * @param string
	 * @return \Entity\Invoice\Invoice
	 */
	public function setClientCompanyId($clientCompanyId)
	{
		$this->clientCompanyId = $clientCompanyId;

		return $this;
	}
		
	/**
	 * @return \Entity\Invoice\Invoice
	 */
	public function unsetClientCompanyId()
	{
		$this->clientCompanyId = NULL;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getClientCompanyId()
	{
		return $this->clientCompanyId;
	}
		
	/**
	 * @param string
	 * @return \Entity\Invoice\Invoice
	 */
	public function setClientCompanyVatId($clientCompanyVatId)
	{
		$this->clientCompanyVatId = $clientCompanyVatId;

		return $this;
	}
		
	/**
	 * @return \Entity\Invoice\Invoice
	 */
	public function unsetClientCompanyVatId()
	{
		$this->clientCompanyVatId = NULL;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getClientCompanyVatId()
	{
		return $this->clientCompanyVatId;
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
}