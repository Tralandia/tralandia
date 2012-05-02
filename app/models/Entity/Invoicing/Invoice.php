<?php

namespace Entity\Invoicing;

use Entity\Company;
use Entity\Dictionary;
use Entity\Invoicing;
use Entity\Rental;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="invoicing_invoice")
 */
class Invoice extends \Entity\BaseEntity {

	/**
	 * @var Collection
	 * @ORM\OneToMany(targetEntity="Item", mappedBy="invoice")
	 */
	protected $items;

	/**
	 * @var integer
	 * @ORM\Column(type="integer")
	 */
	protected $invoiceNumber;

	/**
	 * @var integer
	 * @ORM\Column(type="integer")
	 */
	protected $paymentReferenceNumber;

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
	 * @var boolean
	 * @ORM\Column(type="boolean")
	 */
	protected $checked;

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
	 * @ORM\Column(type="url")
	 */
	protected $clientUrl;

	/**
	 * @var address
	 * @ORM\Column(type="address")
	 */
	protected $clientAddress;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entity\Dictionary\Language")
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
	 * @ORM\Column(type="float")
	 */
	protected $vat;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entity\Currency")
	 */
	protected $currency;

	/**
	 * @var float
	 * @ORM\Column(type="float")
	 */
	protected $exchangeRate;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $createdBy;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $referrer;

	/**
	 * @var float
	 * @ORM\Column(type="float")
	 */
	protected $referrerCommission;

	/**
	 * @var json
	 * @ORM\Column(type="json")
	 */
	protected $paymentInfo;

	















//@entity-generator-code <--- NEMAZAT !!!

	/* ----------------------------- Methods ----------------------------- */		
	public function __construct() {
		parent::__construct();

		$this->items = new \Doctrine\Common\Collections\ArrayCollection;
	}
		
	/**
	 * @param \Entity\Invoicing\Item
	 * @return \Entity\Invoicing\Invoice
	 */
	public function addItem(\Entity\Invoicing\Item $item) {
		if(!$this->items->contains($item)) {
			$this->items->add($item);
		}
		$item->setInvoice($this);

		return $this;
	}
		
	/**
	 * @param \Entity\Invoicing\Item
	 * @return \Entity\Invoicing\Invoice
	 */
	public function removeItem(\Entity\Invoicing\Item $item) {
		if($this->items->contains($item)) {
			$this->items->removeElement($item);
		}
		$item->unsetInvoice();

		return $this;
	}
		
	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection of \Entity\Invoicing\Item
	 */
	public function getItems() {
		return $this->items;
	}
		
	/**
	 * @param integer
	 * @return \Entity\Invoicing\Invoice
	 */
	public function setInvoiceNumber($invoiceNumber) {
		$this->invoiceNumber = $invoiceNumber;

		return $this;
	}
		
	/**
	 * @return integer|NULL
	 */
	public function getInvoiceNumber() {
		return $this->invoiceNumber;
	}
		
	/**
	 * @param integer
	 * @return \Entity\Invoicing\Invoice
	 */
	public function setPaymentReferenceNumber($paymentReferenceNumber) {
		$this->paymentReferenceNumber = $paymentReferenceNumber;

		return $this;
	}
		
	/**
	 * @return integer|NULL
	 */
	public function getPaymentReferenceNumber() {
		return $this->paymentReferenceNumber;
	}
		
	/**
	 * @param \Entity\Company\Company
	 * @return \Entity\Invoicing\Invoice
	 */
	public function setCompany(\Entity\Company\Company $company) {
		$this->company = $company;

		return $this;
	}
		
	/**
	 * @return \Entity\Invoicing\Invoice
	 */
	public function unsetCompany() {
		$this->company = NULL;

		return $this;
	}
		
	/**
	 * @return \Entity\Company\Company|NULL
	 */
	public function getCompany() {
		return $this->company;
	}
		
	/**
	 * @param \Entity\Rental\Rental
	 * @return \Entity\Invoicing\Invoice
	 */
	public function setRental(\Entity\Rental\Rental $rental) {
		$this->rental = $rental;

		return $this;
	}
		
	/**
	 * @return \Entity\Invoicing\Invoice
	 */
	public function unsetRental() {
		$this->rental = NULL;

		return $this;
	}
		
	/**
	 * @return \Entity\Rental\Rental|NULL
	 */
	public function getRental() {
		return $this->rental;
	}
		
	/**
	 * @param \DateTime
	 * @return \Entity\Invoicing\Invoice
	 */
	public function setDue(\DateTime $due) {
		$this->due = $due;

		return $this;
	}
		
	/**
	 * @return \DateTime|NULL
	 */
	public function getDue() {
		return $this->due;
	}
		
	/**
	 * @param \DateTime
	 * @return \Entity\Invoicing\Invoice
	 */
	public function setPaid(\DateTime $paid) {
		$this->paid = $paid;

		return $this;
	}
		
	/**
	 * @return \DateTime|NULL
	 */
	public function getPaid() {
		return $this->paid;
	}
		
	/**
	 * @param boolean
	 * @return \Entity\Invoicing\Invoice
	 */
	public function setChecked($checked) {
		$this->checked = $checked;

		return $this;
	}
		
	/**
	 * @return boolean|NULL
	 */
	public function getChecked() {
		return $this->checked;
	}
		
	/**
	 * @param string
	 * @return \Entity\Invoicing\Invoice
	 */
	public function setClientName($clientName) {
		$this->clientName = $clientName;

		return $this;
	}
		
	/**
	 * @return \Entity\Invoicing\Invoice
	 */
	public function unsetClientName() {
		$this->clientName = NULL;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getClientName() {
		return $this->clientName;
	}
		
	/**
	 * @param string
	 * @return \Entity\Invoicing\Invoice
	 */
	public function setClientPhone($clientPhone) {
		$this->clientPhone = $clientPhone;

		return $this;
	}
		
	/**
	 * @return \Entity\Invoicing\Invoice
	 */
	public function unsetClientPhone() {
		$this->clientPhone = NULL;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getClientPhone() {
		return $this->clientPhone;
	}
		
	/**
	 * @param string
	 * @return \Entity\Invoicing\Invoice
	 */
	public function setClientEmail($clientEmail) {
		$this->clientEmail = $clientEmail;

		return $this;
	}
		
	/**
	 * @return \Entity\Invoicing\Invoice
	 */
	public function unsetClientEmail() {
		$this->clientEmail = NULL;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getClientEmail() {
		return $this->clientEmail;
	}
		
	/**
	 * @param \Extras\Types\Url
	 * @return \Entity\Invoicing\Invoice
	 */
	public function setClientUrl(\Extras\Types\Url $clientUrl) {
		$this->clientUrl = $clientUrl;

		return $this;
	}
		
	/**
	 * @return \Extras\Types\Url|NULL
	 */
	public function getClientUrl() {
		return $this->clientUrl;
	}
		
	/**
	 * @param \Extras\Types\Address
	 * @return \Entity\Invoicing\Invoice
	 */
	public function setClientAddress(\Extras\Types\Address $clientAddress) {
		$this->clientAddress = $clientAddress;

		return $this;
	}
		
	/**
	 * @return \Extras\Types\Address|NULL
	 */
	public function getClientAddress() {
		return $this->clientAddress;
	}
		
	/**
	 * @param \Entity\Dictionary\Language
	 * @return \Entity\Invoicing\Invoice
	 */
	public function setClientLanguage(\Entity\Dictionary\Language $clientLanguage) {
		$this->clientLanguage = $clientLanguage;

		return $this;
	}
		
	/**
	 * @return \Entity\Invoicing\Invoice
	 */
	public function unsetClientLanguage() {
		$this->clientLanguage = NULL;

		return $this;
	}
		
	/**
	 * @return \Entity\Dictionary\Language|NULL
	 */
	public function getClientLanguage() {
		return $this->clientLanguage;
	}
		
	/**
	 * @param string
	 * @return \Entity\Invoicing\Invoice
	 */
	public function setClientCompanyName($clientCompanyName) {
		$this->clientCompanyName = $clientCompanyName;

		return $this;
	}
		
	/**
	 * @return \Entity\Invoicing\Invoice
	 */
	public function unsetClientCompanyName() {
		$this->clientCompanyName = NULL;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getClientCompanyName() {
		return $this->clientCompanyName;
	}
		
	/**
	 * @param string
	 * @return \Entity\Invoicing\Invoice
	 */
	public function setClientCompanyId($clientCompanyId) {
		$this->clientCompanyId = $clientCompanyId;

		return $this;
	}
		
	/**
	 * @return \Entity\Invoicing\Invoice
	 */
	public function unsetClientCompanyId() {
		$this->clientCompanyId = NULL;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getClientCompanyId() {
		return $this->clientCompanyId;
	}
		
	/**
	 * @param string
	 * @return \Entity\Invoicing\Invoice
	 */
	public function setClientCompanyVatId($clientCompanyVatId) {
		$this->clientCompanyVatId = $clientCompanyVatId;

		return $this;
	}
		
	/**
	 * @return \Entity\Invoicing\Invoice
	 */
	public function unsetClientCompanyVatId() {
		$this->clientCompanyVatId = NULL;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getClientCompanyVatId() {
		return $this->clientCompanyVatId;
	}
		
	/**
	 * @param float
	 * @return \Entity\Invoicing\Invoice
	 */
	public function setVat($vat) {
		$this->vat = $vat;

		return $this;
	}
		
	/**
	 * @return float|NULL
	 */
	public function getVat() {
		return $this->vat;
	}
		
	/**
	 * @param decimal
	 * @return \Entity\Invoicing\Invoice
	 */
	public function setExchangeRate($exchangeRate) {
		$this->exchangeRate = $exchangeRate;

		return $this;
	}
		
	/**
	 * @return decimal|NULL
	 */
	public function getExchangeRate() {
		return $this->exchangeRate;
	}
		
	/**
	 * @param string
	 * @return \Entity\Invoicing\Invoice
	 */
	public function setCreatedBy($createdBy) {
		$this->createdBy = $createdBy;

		return $this;
	}
		
	/**
	 * @return \Entity\Invoicing\Invoice
	 */
	public function unsetCreatedBy() {
		$this->createdBy = NULL;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getCreatedBy() {
		return $this->createdBy;
	}
		
	/**
	 * @param string
	 * @return \Entity\Invoicing\Invoice
	 */
	public function setReferrer($referrer) {
		$this->referrer = $referrer;

		return $this;
	}
		
	/**
	 * @return \Entity\Invoicing\Invoice
	 */
	public function unsetReferrer() {
		$this->referrer = NULL;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getReferrer() {
		return $this->referrer;
	}
		
	/**
	 * @param decimal
	 * @return \Entity\Invoicing\Invoice
	 */
	public function setReferrerCommission($referrerCommission) {
		$this->referrerCommission = $referrerCommission;

		return $this;
	}
		
	/**
	 * @return decimal|NULL
	 */
	public function getReferrerCommission() {
		return $this->referrerCommission;
	}
		
	/**
	 * @param json
	 * @return \Entity\Invoicing\Invoice
	 */
	public function setPaymentInfo($paymentInfo) {
		$this->paymentInfo = $paymentInfo;

		return $this;
	}
		
	/**
	 * @return json|NULL
	 */
	public function getPaymentInfo() {
		return $this->paymentInfo;
	}
}