<?php

namespace Invoicing;

use Company;
use Dictionary;
use Invoicing;
use Rental;
use Doctrine\Common\Collections\Collection
use Doctrine\Common\Collections\ArrayCollection


/**
 * @Entity()
 * @Table(name="invoicing_invoice")
 */
class Invoice extends \BaseEntity {

	/**
	 * @var Collection
	 * @Column(type="Item")
	 */
	protected $items;

	/**
	 * @var integer
	 * @Column(type="integer")
	 */
	protected $invoiceNumber;

	/**
	 * @var integer
	 * @Column(type="integer")
	 */
	protected $invoiceVariableNumber;

	/**
	 * @var Collection
	 * @ManyToOne(targetEntity="Company\Company", inversedBy="invoices")
	 */
	protected $invoicingCompany;

	/**
	 * @var Collection
	 * @ManyToOne(targetEntity="Rental\Rental", inversedBy="invoices")
	 */
	protected $rental;

	/**
	 * @var datetime
	 * @Column(type="datetime")
	 */
	protected $due;

	/**
	 * @var datetime
	 * @Column(type="datetime")
	 */
	protected $paid;

	/**
	 * @var boolean
	 * @Column(type="boolean")
	 */
	protected $checked;

	/**
	 * @var string
	 * @Column(type="string", nullable=true)
	 */
	protected $clientName;

	/**
	 * @var string
	 * @Column(type="string", nullable=true)
	 */
	protected $clientPhone;

	/**
	 * @var string
	 * @Column(type="string", nullable=true)
	 */
	protected $clientEmail;

	/**
	 * @var url
	 * @Column(type="url")
	 */
	protected $clientUrl;

	/**
	 * @var address
	 * @Column(type="address")
	 */
	protected $clientAddress;

	/**
	 * @var Collection
	 * @ManyToMany(targetEntity="Dictionary\Language")
	 */
	protected $clientLanguage;

	/**
	 * @var string
	 * @Column(type="string", nullable=true)
	 */
	protected $clientCompanyName;

	/**
	 * @var string
	 * @Column(type="string", nullable=true)
	 */
	protected $clientCompanyId;

	/**
	 * @var string
	 * @Column(type="string", nullable=true)
	 */
	protected $clientCompanyVatId;

	/**
	 * @var decimal
	 * @Column(type="decimal")
	 */
	protected $vat;

	/**
	 * @var decimal
	 * @Column(type="decimal")
	 */
	protected $exchange_rate;

	/**
	 * @var string
	 * @Column(type="string", nullable=true)
	 */
	protected $createdBy;

	/**
	 * @var string
	 * @Column(type="string", nullable=true)
	 */
	protected $referrer;

	/**
	 * @var decimal
	 * @Column(type="decimal")
	 */
	protected $referrerCommission;

	/**
	 * @var json
	 * @Column(type="json")
	 */
	protected $paymentInfo;


	public function __construct() {

	}


	/**
	 * @param Item $items
	 * @return Invoice
	 */
	public function setItems(Item  $items) {
		$this->items = $items;
		return $this;
	}


	/**
	 * @return Item
	 */
	public function getItems() {
		return $this->items;
	}


	/**
	 * @param integer $invoiceNumber
	 * @return Invoice
	 */
	public function setInvoiceNumber($invoiceNumber) {
		$this->invoiceNumber = $invoiceNumber;
		return $this;
	}


	/**
	 * @return integer
	 */
	public function getInvoiceNumber() {
		return $this->invoiceNumber;
	}


	/**
	 * @param integer $invoiceVariableNumber
	 * @return Invoice
	 */
	public function setInvoiceVariableNumber($invoiceVariableNumber) {
		$this->invoiceVariableNumber = $invoiceVariableNumber;
		return $this;
	}


	/**
	 * @return integer
	 */
	public function getInvoiceVariableNumber() {
		return $this->invoiceVariableNumber;
	}


	/**
	 * @param Company\Company $invoicingCompany
	 * @return Invoice
	 */
	public function setInvoicingCompany(Company\Company  $invoicingCompany) {
		$this->invoicingCompany = $invoicingCompany;
		return $this;
	}


	/**
	 * @return Company\Company
	 */
	public function getInvoicingCompany() {
		return $this->invoicingCompany;
	}


	/**
	 * @param Rental\Rental $rental
	 * @return Invoice
	 */
	public function setRental(Rental\Rental  $rental) {
		$this->rental = $rental;
		return $this;
	}


	/**
	 * @return Rental\Rental
	 */
	public function getRental() {
		return $this->rental;
	}


	/**
	 * @param datetime $due
	 * @return Invoice
	 */
	public function setDue($due) {
		$this->due = $due;
		return $this;
	}


	/**
	 * @return datetime
	 */
	public function getDue() {
		return $this->due;
	}


	/**
	 * @param datetime $paid
	 * @return Invoice
	 */
	public function setPaid($paid) {
		$this->paid = $paid;
		return $this;
	}


	/**
	 * @return datetime
	 */
	public function getPaid() {
		return $this->paid;
	}


	/**
	 * @param boolean $checked
	 * @return Invoice
	 */
	public function setChecked($checked) {
		$this->checked = $checked;
		return $this;
	}


	/**
	 * @return boolean
	 */
	public function getChecked() {
		return $this->checked;
	}


	/**
	 * @param string $clientName
	 * @return Invoice
	 */
	public function setClientName($clientName) {
		$this->clientName = $clientName;
		return $this;
	}


	/**
	 * @return string
	 */
	public function getClientName() {
		return $this->clientName;
	}


	/**
	 * @param string $clientPhone
	 * @return Invoice
	 */
	public function setClientPhone($clientPhone) {
		$this->clientPhone = $clientPhone;
		return $this;
	}


	/**
	 * @return string
	 */
	public function getClientPhone() {
		return $this->clientPhone;
	}


	/**
	 * @param string $clientEmail
	 * @return Invoice
	 */
	public function setClientEmail($clientEmail) {
		$this->clientEmail = $clientEmail;
		return $this;
	}


	/**
	 * @return string
	 */
	public function getClientEmail() {
		return $this->clientEmail;
	}


	/**
	 * @param url $clientUrl
	 * @return Invoice
	 */
	public function setClientUrl($clientUrl) {
		$this->clientUrl = $clientUrl;
		return $this;
	}


	/**
	 * @return url
	 */
	public function getClientUrl() {
		return $this->clientUrl;
	}


	/**
	 * @param address $clientAddress
	 * @return Invoice
	 */
	public function setClientAddress($clientAddress) {
		$this->clientAddress = $clientAddress;
		return $this;
	}


	/**
	 * @return address
	 */
	public function getClientAddress() {
		return $this->clientAddress;
	}


	/**
	 * @param Dictionary\Language $clientLanguage
	 * @return Invoice
	 */
	public function setClientLanguage(Dictionary\Language  $clientLanguage) {
		$this->clientLanguage = $clientLanguage;
		return $this;
	}


	/**
	 * @return Dictionary\Language
	 */
	public function getClientLanguage() {
		return $this->clientLanguage;
	}


	/**
	 * @param string $clientCompanyName
	 * @return Invoice
	 */
	public function setClientCompanyName($clientCompanyName) {
		$this->clientCompanyName = $clientCompanyName;
		return $this;
	}


	/**
	 * @return string
	 */
	public function getClientCompanyName() {
		return $this->clientCompanyName;
	}


	/**
	 * @param string $clientCompanyId
	 * @return Invoice
	 */
	public function setClientCompanyId($clientCompanyId) {
		$this->clientCompanyId = $clientCompanyId;
		return $this;
	}


	/**
	 * @return string
	 */
	public function getClientCompanyId() {
		return $this->clientCompanyId;
	}


	/**
	 * @param string $clientCompanyVatId
	 * @return Invoice
	 */
	public function setClientCompanyVatId($clientCompanyVatId) {
		$this->clientCompanyVatId = $clientCompanyVatId;
		return $this;
	}


	/**
	 * @return string
	 */
	public function getClientCompanyVatId() {
		return $this->clientCompanyVatId;
	}


	/**
	 * @param decimal $vat
	 * @return Invoice
	 */
	public function setVat($vat) {
		$this->vat = $vat;
		return $this;
	}


	/**
	 * @return decimal
	 */
	public function getVat() {
		return $this->vat;
	}


	/**
	 * @param decimal $exchange_rate
	 * @return Invoice
	 */
	public function setExchange_rate($exchange_rate) {
		$this->exchange_rate = $exchange_rate;
		return $this;
	}


	/**
	 * @return decimal
	 */
	public function getExchange_rate() {
		return $this->exchange_rate;
	}


	/**
	 * @param string $createdBy
	 * @return Invoice
	 */
	public function setCreatedBy($createdBy) {
		$this->createdBy = $createdBy;
		return $this;
	}


	/**
	 * @return string
	 */
	public function getCreatedBy() {
		return $this->createdBy;
	}


	/**
	 * @param string $referrer
	 * @return Invoice
	 */
	public function setReferrer($referrer) {
		$this->referrer = $referrer;
		return $this;
	}


	/**
	 * @return string
	 */
	public function getReferrer() {
		return $this->referrer;
	}


	/**
	 * @param decimal $referrerCommission
	 * @return Invoice
	 */
	public function setReferrerCommission($referrerCommission) {
		$this->referrerCommission = $referrerCommission;
		return $this;
	}


	/**
	 * @return decimal
	 */
	public function getReferrerCommission() {
		return $this->referrerCommission;
	}


	/**
	 * @param json $paymentInfo
	 * @return Invoice
	 */
	public function setPaymentInfo($paymentInfo) {
		$this->paymentInfo = $paymentInfo;
		return $this;
	}


	/**
	 * @return json
	 */
	public function getPaymentInfo() {
		return $this->paymentInfo;
	}

}
