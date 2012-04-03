<?php

namespace Entities\Company;

use Entities\Location;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="company_bankaccount")
 */
class BankAccount extends \Entities\BaseEntity {

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Entities\Location\Location", mappedBy="bankAccounts")
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

	/* ----------------------------- Methods ----------------------------- */


	public function __construct() {
		parent::__construct();

		$this->countries = new \Doctrine\Common\Collections\ArrayCollection;
	}

	/**
	 * @param \Entities\Location\Location
	 * @return \Entities\Company\BankAccount
	 */
	public function addCountry(\Entities\Location\Location $country) {
		if(!$this->countries->contains($country)) {
			$this->countries->add($country);
		}
		$country->addBankAccount($this);

		return $this;
	}

	/**
	 * @param \Entities\Location\Location
	 * @return \Entities\Company\BankAccount
	 */
	public function removeCountry(\Entities\Location\Location $country) {
		if($this->countries->contains($country)) {
			$this->countries->removeElement($country);
		}
		$country->removeBankAccount($this);

		return $this;
	}

	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection of \Entities\Location\Location
	 */
	public function getCountries() {
		return $this->countries;
	}

	/**
	 * @param \Entities\Company\Company
	 * @return \Entities\Company\BankAccount
	 */
	public function setCompany(\Entities\Company\Company $company) {
		$this->company = $company;

		return $this;
	}

	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection of \Entities\Company\Company
	 */
	public function getCompany() {
		return $this->company;
	}

	/**
	 * @param string
	 * @return \Entities\Company\BankAccount
	 */
	public function setBankName($bankName) {
		$this->bankName = $bankName;

		return $this;
	}

	/**
	 * @return \Entities\Company\BankAccount
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
	 * @return \Entities\Company\BankAccount
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
	 * @return \Entities\Company\BankAccount
	 */
	public function setBankSwift($bankSwift) {
		$this->bankSwift = $bankSwift;

		return $this;
	}

	/**
	 * @return \Entities\Company\BankAccount
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
	 * @return \Entities\Company\BankAccount
	 */
	public function setAccountNumber($accountNumber) {
		$this->accountNumber = $accountNumber;

		return $this;
	}

	/**
	 * @return \Entities\Company\BankAccount
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
	 * @return \Entities\Company\BankAccount
	 */
	public function setAccountName($accountName) {
		$this->accountName = $accountName;

		return $this;
	}

	/**
	 * @return \Entities\Company\BankAccount
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
	 * @return \Entities\Company\BankAccount
	 */
	public function setAccountIban($accountIban) {
		$this->accountIban = $accountIban;

		return $this;
	}

	/**
	 * @return \Entities\Company\BankAccount
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
	 * @return \Entities\Company\BankAccount
	 */
	public function setNotes($notes) {
		$this->notes = $notes;

		return $this;
	}

	/**
	 * @return string|NULL
	 */
	public function getNotes() {
		return $this->notes;
	}

}