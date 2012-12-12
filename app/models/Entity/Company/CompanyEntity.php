<?php

namespace Entity\Company;

use Entity\Phrase;
use Entity\Invoice;
use Entity\Location;
use Entity\Medium;
use Doctrine\ORM\Mapping as ORM;
use	Extras\Annotation as EA;

/**
 * @ORM\Entity()
 * @ORM\Table(name="company")
 * @EA\Primary(key="id", value="name")
 */
class Company extends \Entity\BaseEntityDetails {

	/**
	 * @var Collection
	 * @ORM\OneToMany(targetEntity="BankAccount", mappedBy="company", cascade={"persist", "remove"})
	 */
	protected $bankAccounts;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Entity\Location\Location", mappedBy="companies", cascade={"persist"})
	 */
	protected $countries;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $name;

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Entity\Contact\Address", cascade={"persist", "remove"})
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
	 * @ORM\OneToOne(targetEntity="Entity\Phrase\Phrase", cascade={"persist", "remove"})
	 */
	protected $registrator;

	/**
	 * @var Collection
	 * @ORM\OneToMany(targetEntity="Entity\Invoice\Invoice", mappedBy="company")
	 */
	protected $invoices;

								//@entity-generator-code --- NEMAZAT !!!

	/* ----------------------------- Methods ----------------------------- */		
	public function __construct()
	{
		parent::__construct();

		$this->bankAccounts = new \Doctrine\Common\Collections\ArrayCollection;
		$this->countries = new \Doctrine\Common\Collections\ArrayCollection;
		$this->invoices = new \Doctrine\Common\Collections\ArrayCollection;
	}
		
	/**
	 * @param \Entity\Company\BankAccount
	 * @return \Entity\Company\Company
	 */
	public function addBankAccount(\Entity\Company\BankAccount $bankAccount)
	{
		if(!$this->bankAccounts->contains($bankAccount)) {
			$this->bankAccounts->add($bankAccount);
		}
		$bankAccount->setCompany($this);

		return $this;
	}
		
	/**
	 * @param \Entity\Company\BankAccount
	 * @return \Entity\Company\Company
	 */
	public function removeBankAccount(\Entity\Company\BankAccount $bankAccount)
	{
		if($this->bankAccounts->contains($bankAccount)) {
			$this->bankAccounts->removeElement($bankAccount);
		}
		$bankAccount->unsetCompany();

		return $this;
	}
		
	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection of \Entity\Company\BankAccount
	 */
	public function getBankAccounts()
	{
		return $this->bankAccounts;
	}
		
	/**
	 * @param \Entity\Location\Location
	 * @return \Entity\Company\Company
	 */
	public function addCountry(\Entity\Location\Location $country)
	{
		if(!$this->countries->contains($country)) {
			$this->countries->add($country);
		}
		$country->addCompany($this);

		return $this;
	}
		
	/**
	 * @param \Entity\Location\Location
	 * @return \Entity\Company\Company
	 */
	public function removeCountry(\Entity\Location\Location $country)
	{
		if($this->countries->contains($country)) {
			$this->countries->removeElement($country);
		}
		$country->removeCompany($this);

		return $this;
	}
		
	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection of \Entity\Location\Location
	 */
	public function getCountries()
	{
		return $this->countries;
	}
		
	/**
	 * @param string
	 * @return \Entity\Company\Company
	 */
	public function setName($name)
	{
		$this->name = $name;

		return $this;
	}
		
	/**
	 * @return \Entity\Company\Company
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
	 * @param \Entity\Contact\Address
	 * @return \Entity\Company\Company
	 */
	public function setAddress(\Entity\Contact\Address $address)
	{
		$this->address = $address;

		return $this;
	}
		
	/**
	 * @return \Entity\Contact\Address|NULL
	 */
	public function getAddress()
	{
		return $this->address;
	}
		
	/**
	 * @param string
	 * @return \Entity\Company\Company
	 */
	public function setCompanyId($companyId)
	{
		$this->companyId = $companyId;

		return $this;
	}
		
	/**
	 * @return \Entity\Company\Company
	 */
	public function unsetCompanyId()
	{
		$this->companyId = NULL;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getCompanyId()
	{
		return $this->companyId;
	}
		
	/**
	 * @param string
	 * @return \Entity\Company\Company
	 */
	public function setCompanyVatId($companyVatId)
	{
		$this->companyVatId = $companyVatId;

		return $this;
	}
		
	/**
	 * @return \Entity\Company\Company
	 */
	public function unsetCompanyVatId()
	{
		$this->companyVatId = NULL;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getCompanyVatId()
	{
		return $this->companyVatId;
	}
		
	/**
	 * @param float
	 * @return \Entity\Company\Company
	 */
	public function setVat($vat)
	{
		$this->vat = $vat;

		return $this;
	}
		
	/**
	 * @return \Entity\Company\Company
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
	 * @param \Entity\Phrase\Phrase
	 * @return \Entity\Company\Company
	 */
	public function setRegistrator(\Entity\Phrase\Phrase $registrator)
	{
		$this->registrator = $registrator;

		return $this;
	}
		
	/**
	 * @return \Entity\Phrase\Phrase|NULL
	 */
	public function getRegistrator()
	{
		return $this->registrator;
	}
		
	/**
	 * @param \Entity\Invoice\Invoice
	 * @return \Entity\Company\Company
	 */
	public function addInvoice(\Entity\Invoice\Invoice $invoice)
	{
		if(!$this->invoices->contains($invoice)) {
			$this->invoices->add($invoice);
		}
		$invoice->setCompany($this);

		return $this;
	}
		
	/**
	 * @param \Entity\Invoice\Invoice
	 * @return \Entity\Company\Company
	 */
	public function removeInvoice(\Entity\Invoice\Invoice $invoice)
	{
		if($this->invoices->contains($invoice)) {
			$this->invoices->removeElement($invoice);
		}
		$invoice->unsetCompany();

		return $this;
	}
		
	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection of \Entity\Invoice\Invoice
	 */
	public function getInvoices()
	{
		return $this->invoices;
	}
}