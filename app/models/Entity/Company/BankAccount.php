<?php

namespace Entity\Company;

use Entity\Location;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="company_bankaccount")
 */
class BankAccount extends \Entity\BaseEntity {

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Entity\Location\Location", mappedBy="bankAccounts")
	 */
	protected $countries;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Company", inversedBy="bankAccounts")
	 */
	protected $company;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $bankName;

	/**
	 * @var address
	 * @ORM\Column(type="address")
	 */
	protected $bankAddress;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $bankSwift;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $accountNumber;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $accountName;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $accountIban;

	/**
	 * @var text
	 * @ORM\Column(type="text", nullable=true)
	 */
	protected $notes;

	






//@entity-generator-code <--- NEMAZAT !!!

	/* ----------------------------- Methods ----------------------------- */		
	public function __construct() {
		parent::__construct();

		$this->countries = new \Doctrine\Common\Collections\ArrayCollection;
	}
		
	/**
	 * @param \Entity\Location\Location
	 * @return \Entity\Company\BankAccount
	 */
	public function addCountry(\Entity\Location\Location $country) {
		if(!$this->countries->contains($country)) {
			$this->countries->add($country);
		}
		$country->addBankAccount($this);

		return $this;
	}
		
	/**
	 * @param \Entity\Location\Location
	 * @return \Entity\Company\BankAccount
	 */
	public function removeCountry(\Entity\Location\Location $country) {
		if($this->countries->contains($country)) {
			$this->countries->removeElement($country);
		}
		$country->removeBankAccount($this);

		return $this;
	}
		
	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection of \Entity\Location\Location
	 */
	public function getCountries() {
		return $this->countries;
	}
		
	/**
	 * @param \Entity\Company\Company
	 * @return \Entity\Company\BankAccount
	 */
	public function setCompany(\Entity\Company\Company $company) {
		$this->company = $company;

		return $this;
	}
		
	/**
	 * @return \Entity\Company\BankAccount
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
	 * @param string
	 * @return \Entity\Company\BankAccount
	 */
	public function setBankName($bankName) {
		$this->bankName = $bankName;

		return $this;
	}
		
	/**
	 * @return \Entity\Company\BankAccount
	 */
	public function unsetBankName() {
		$this->bankName = NULL;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getBankName() {
		return $this->bankName;
	}
		
	/**
	 * @param \Extras\Types\Address
	 * @return \Entity\Company\BankAccount
	 */
	public function setBankAddress(\Extras\Types\Address $bankAddress) {
		$this->bankAddress = $bankAddress;

		return $this;
	}
		
	/**
	 * @return \Extras\Types\Address|NULL
	 */
	public function getBankAddress() {
		return $this->bankAddress;
	}
		
	/**
	 * @param string
	 * @return \Entity\Company\BankAccount
	 */
	public function setBankSwift($bankSwift) {
		$this->bankSwift = $bankSwift;

		return $this;
	}
		
	/**
	 * @return \Entity\Company\BankAccount
	 */
	public function unsetBankSwift() {
		$this->bankSwift = NULL;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getBankSwift() {
		return $this->bankSwift;
	}
		
	/**
	 * @param string
	 * @return \Entity\Company\BankAccount
	 */
	public function setAccountNumber($accountNumber) {
		$this->accountNumber = $accountNumber;

		return $this;
	}
		
	/**
	 * @return \Entity\Company\BankAccount
	 */
	public function unsetAccountNumber() {
		$this->accountNumber = NULL;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getAccountNumber() {
		return $this->accountNumber;
	}
		
	/**
	 * @param string
	 * @return \Entity\Company\BankAccount
	 */
	public function setAccountName($accountName) {
		$this->accountName = $accountName;

		return $this;
	}
		
	/**
	 * @return \Entity\Company\BankAccount
	 */
	public function unsetAccountName() {
		$this->accountName = NULL;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getAccountName() {
		return $this->accountName;
	}
		
	/**
	 * @param string
	 * @return \Entity\Company\BankAccount
	 */
	public function setAccountIban($accountIban) {
		$this->accountIban = $accountIban;

		return $this;
	}
		
	/**
	 * @return \Entity\Company\BankAccount
	 */
	public function unsetAccountIban() {
		$this->accountIban = NULL;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getAccountIban() {
		return $this->accountIban;
	}
		
	/**
	 * @param string
	 * @return \Entity\Company\BankAccount
	 */
	public function setNotes($notes) {
		$this->notes = $notes;

		return $this;
	}
		
	/**
	 * @return \Entity\Company\BankAccount
	 */
	public function unsetNotes() {
		$this->notes = NULL;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getNotes() {
		return $this->notes;
	}
}