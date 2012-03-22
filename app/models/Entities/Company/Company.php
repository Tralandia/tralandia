<?php

namespace Entities\Company;

use Entities\Dictionary;
use Entities\Invoicing;
use Entities\Location;
use Entities\Medium;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="company_company")
 */
class Company extends \Entities\BaseEntityDetails {

	/**
	 * @var Collection
	 * @ORM\OneToMany(targetEntity="BankAccount", mappedBy="company", cascade={"persist", "remove"})
	 */
	protected $bankAccounts;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Entities\Location\Location", mappedBy="companies")
	 */
	protected $countries;

	/**
	 * @var Collection
	 * @ORM\OneToMany(targetEntity="Office", mappedBy="company", cascade={"persist", "remove"})
	 */
	protected $offices;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $name;

	/**
	 * @var address
	 * @ORM\Column(type="address", nullable=true)
	 */
	protected $address;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $companyId;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $companyVatId;

	/**
	 * @var float
	 * @ORM\Column(type="float", nullable=true)
	 */
	protected $vat;

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Entities\Dictionary\Phrase", cascade={"persist", "remove"})
	 */
	protected $registrator;

	/**
	 * @var Collection
	 * @ORM\OneToMany(targetEntity="Entities\Invoicing\Invoice", mappedBy="invoicingCompany")
	 */
	protected $invoices;

	/* ----------------------------- Methods ----------------------------- */


	public function __construct() {
		parent::__construct();

		$this->bankAccounts = new \Doctrine\Common\Collections\ArrayCollection;
		$this->countries = new \Doctrine\Common\Collections\ArrayCollection;
		$this->offices = new \Doctrine\Common\Collections\ArrayCollection;
		$this->invoices = new \Doctrine\Common\Collections\ArrayCollection;
	}

	/**
	 * @param \Entities\Company\BankAccount
	 * @return \Entities\Company\Company
	 */
	public function addBankAccount(\Entities\Company\BankAccount $bankAccount) {
		if(!$this->bankAccounts->contains($bankAccount)) {
			$this->bankAccounts->add($bankAccount);
		}
		$bankAccount->setCompany($this);

		return $this;
	}

	/**
	 * @param \Entities\Company\BankAccount
	 * @return \Entities\Company\Company
	 */
	public function removeBankAccount(\Entities\Company\BankAccount $bankAccount) {
		if($this->bankAccounts->contains($bankAccount)) {
			$this->bankAccounts->removeElement($bankAccount);
		}
		$bankAccount->unsetCompany();

		return $this;
	}

	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection of \Entities\Company\BankAccount
	 */
	public function getBankAccounts() {
		return $this->bankAccounts;
	}

	/**
	 * @param \Entities\Location\Location
	 * @return \Entities\Company\Company
	 */
	public function addCountry(\Entities\Location\Location $country) {
		if(!$this->countries->contains($country)) {
			$this->countries->add($country);
		}
		$country->addCompany($this);

		return $this;
	}

	/**
	 * @param \Entities\Location\Location
	 * @return \Entities\Company\Company
	 */
	public function removeCountry(\Entities\Location\Location $country) {
		if($this->countries->contains($country)) {
			$this->countries->removeElement($country);
		}
		$country->removeCompany($this);

		return $this;
	}

	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection of \Entities\Location\Location
	 */
	public function getCountries() {
		return $this->countries;
	}

	/**
	 * @param \Entities\Company\Office
	 * @return \Entities\Company\Company
	 */
	public function addOffice(\Entities\Company\Office $office) {
		if(!$this->offices->contains($office)) {
			$this->offices->add($office);
		}
		$office->setCompany($this);

		return $this;
	}

	/**
	 * @param \Entities\Company\Office
	 * @return \Entities\Company\Company
	 */
	public function removeOffice(\Entities\Company\Office $office) {
		if($this->offices->contains($office)) {
			$this->offices->removeElement($office);
		}
		$office->unsetCompany();

		return $this;
	}

	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection of \Entities\Company\Office
	 */
	public function getOffices() {
		return $this->offices;
	}

	/**
	 * @param string
	 * @return \Entities\Company\Company
	 */
	public function setName($name) {
		$this->name = $name;

		return $this;
	}

	/**
	 * @return \Entities\Company\Company
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
	 * @param \Extras\Types\Address
	 * @return \Entities\Company\Company
	 */
	public function setAddress(\Extras\Types\Address $address) {
		$this->address = $address;

		return $this;
	}

	/**
	 * @return \Entities\Company\Company
	 */
	public function unsetAddress() {
		$this->address = NULL;

		return $this;
	}

	/**
	 * @return \Extras\Types\Address|NULL
	 */
	public function getAddress() {
		return $this->address;
	}

	/**
	 * @param string
	 * @return \Entities\Company\Company
	 */
	public function setCompanyId($companyId) {
		$this->companyId = $companyId;

		return $this;
	}

	/**
	 * @return \Entities\Company\Company
	 */
	public function unsetCompanyId() {
		$this->companyId = NULL;

		return $this;
	}

	/**
	 * @return string|NULL
	 */
	public function getCompanyId() {
		return $this->companyId;
	}

	/**
	 * @param string
	 * @return \Entities\Company\Company
	 */
	public function setCompanyVatId($companyVatId) {
		$this->companyVatId = $companyVatId;

		return $this;
	}

	/**
	 * @return \Entities\Company\Company
	 */
	public function unsetCompanyVatId() {
		$this->companyVatId = NULL;

		return $this;
	}

	/**
	 * @return string|NULL
	 */
	public function getCompanyVatId() {
		return $this->companyVatId;
	}

	/**
	 * @param \Extras\Types\Float
	 * @return \Entities\Company\Company
	 */
	public function setVat(\Extras\Types\Float $vat) {
		$this->vat = $vat;

		return $this;
	}

	/**
	 * @return \Entities\Company\Company
	 */
	public function unsetVat() {
		$this->vat = NULL;

		return $this;
	}

	/**
	 * @return \Extras\Types\Float|NULL
	 */
	public function getVat() {
		return $this->vat;
	}

	/**
	 * @param \Entities\Dictionary\Phrase
	 * @return \Entities\Company\Company
	 */
	public function setRegistrator(\Entities\Dictionary\Phrase $registrator) {
		$this->registrator = $registrator;

		return $this;
	}

	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection of \Entities\Dictionary\Phrase
	 */
	public function getRegistrator() {
		return $this->registrator;
	}

	/**
	 * @param \Entities\Invoicing\Invoice
	 * @return \Entities\Company\Company
	 */
	public function addInvoice(\Entities\Invoicing\Invoice $invoice) {
		if(!$this->invoices->contains($invoice)) {
			$this->invoices->add($invoice);
		}
		$invoice->setInvoicingCompany($this);

		return $this;
	}

	/**
	 * @param \Entities\Invoicing\Invoice
	 * @return \Entities\Company\Company
	 */
	public function removeInvoice(\Entities\Invoicing\Invoice $invoice) {
		if($this->invoices->contains($invoice)) {
			$this->invoices->removeElement($invoice);
		}
		$invoice->unsetInvoicingCompany();

		return $this;
	}

	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection of \Entities\Invoicing\Invoice
	 */
	public function getInvoices() {
		return $this->invoices;
	}

}