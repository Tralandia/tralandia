<?php

namespace Company;

use Company;
use Dictionary;
use Invoicing;
use Location;
use Medium;
use Doctrine\Common\Collections\Collection
use Doctrine\Common\Collections\ArrayCollection


/**
 * @Entity()
 * @Table(name="company_company")
 */
class Company extends \BaseEntityDetails {

	/**
	 * @var Collection
	 * @Column(type="BankAccount")
	 */
	protected $accounts;

	/**
	 * @var Collection
	 * @ManyToMany(targetEntity="Location\Location")
	 */
	protected $countries;

	/**
	 * @var Collection
	 * @Column(type="Office")
	 */
	protected $offices;

	/**
	 * @var string
	 * @Column(type="string", nullable=true)
	 */
	protected $name;

	/**
	 * @var address
	 * @Column(type="address")
	 */
	protected $address;

	/**
	 * @var string
	 * @Column(type="string", nullable=true)
	 */
	protected $companyId;

	/**
	 * @var string
	 * @Column(type="string", nullable=true)
	 */
	protected $companyVatId;

	/**
	 * @var decimal
	 * @Column(type="decimal")
	 */
	protected $vat;

	/**
	 * @var Collection
	 * @OneToOne(targetEntity="Dictionary\Phrase")
	 */
	protected $registrator;

	/**
	 * @var Collection
	 * @OneToOne(targetEntity="Medium\Medium")
	 */
	protected $signature;

	/**
	 * @var Collection
	 * @OneToMany(targetEntity="Invoicing\Invoice", mappedBy="invoicingCompany")
	 */
	protected $invoices;


	public function __construct() {
		$this->countries = new ArrayCollection();
		$this->invoices = new ArrayCollection();
	}


	/**
	 * @param BankAccount $accounts
	 * @return Company
	 */
	public function setAccounts(BankAccount  $accounts) {
		$this->accounts = $accounts;
		return $this;
	}


	/**
	 * @return BankAccount
	 */
	public function getAccounts() {
		return $this->accounts;
	}


	/**
	 * @param Location\Location $country
	 * @return Company
	 */
	public function addCountry(Location\Location  $country) {
		$this->countries->add($country);
		return $this;
	}


	/**
	 * @param Location\Location $country
	 * @return Company
	 */
	public function removeCountry(Location\Location  $country) {
		$this->countries->removeElement($country);
		return $this;
	}


	/**
	 * @return Location\Location[]
	 */
	public function getCountry() {
		return $this->countries->toArray();
	}


	/**
	 * @param Office $offices
	 * @return Company
	 */
	public function setOffices(Office  $offices) {
		$this->offices = $offices;
		return $this;
	}


	/**
	 * @return Office
	 */
	public function getOffices() {
		return $this->offices;
	}


	/**
	 * @param string $name
	 * @return Company
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
	 * @param address $address
	 * @return Company
	 */
	public function setAddress($address) {
		$this->address = $address;
		return $this;
	}


	/**
	 * @return address
	 */
	public function getAddress() {
		return $this->address;
	}


	/**
	 * @param string $companyId
	 * @return Company
	 */
	public function setCompanyId($companyId) {
		$this->companyId = $companyId;
		return $this;
	}


	/**
	 * @return string
	 */
	public function getCompanyId() {
		return $this->companyId;
	}


	/**
	 * @param string $companyVatId
	 * @return Company
	 */
	public function setCompanyVatId($companyVatId) {
		$this->companyVatId = $companyVatId;
		return $this;
	}


	/**
	 * @return string
	 */
	public function getCompanyVatId() {
		return $this->companyVatId;
	}


	/**
	 * @param decimal $vat
	 * @return Company
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
	 * @param Dictionary\Phrase $registrator
	 * @return Company
	 */
	public function setRegistrator(Dictionary\Phrase  $registrator) {
		$this->registrator = $registrator;
		return $this;
	}


	/**
	 * @return Dictionary\Phrase
	 */
	public function getRegistrator() {
		return $this->registrator;
	}


	/**
	 * @param Medium\Medium $signature
	 * @return Company
	 */
	public function setSignature(Medium\Medium  $signature) {
		$this->signature = $signature;
		return $this;
	}


	/**
	 * @return Medium\Medium
	 */
	public function getSignature() {
		return $this->signature;
	}


	/**
	 * @param Invoicing\Invoice $invoice
	 * @return Company
	 */
	public function addInvoice(Invoicing\Invoice  $invoice) {
		$this->invoices->add($invoice);
		return $this;
	}


	/**
	 * @param Invoicing\Invoice $invoice
	 * @return Company
	 */
	public function removeInvoice(Invoicing\Invoice  $invoice) {
		$this->invoices->removeElement($invoice);
		return $this;
	}


	/**
	 * @return Invoicing\Invoice[]
	 */
	public function getInvoice() {
		return $this->invoices->toArray();
	}

}
