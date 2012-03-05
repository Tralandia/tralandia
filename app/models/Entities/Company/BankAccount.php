<?php

namespace Entities\Company;

use Entities\Company;
use Entities\Location;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="company_bankaccount")
 */
class BankAccount extends \BaseEntity {

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Location\Location")
	 */
	protected $countries;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(type="Company")
	 */
	protected $company;

	/**
	 * @var string
	 * @ORM\ManyToMany(type="string", nullable=true)
	 */
	protected $bankName;

	/**
	 * @var address
	 * @ORM\ManyToMany(type="address")
	 */
	protected $bankAddress;

	/**
	 * @var string
	 * @ORM\ManyToMany(type="string", nullable=true)
	 */
	protected $bankSwift;

	/**
	 * @var string
	 * @ORM\ManyToMany(type="string", nullable=true)
	 */
	protected $accountNumber;

	/**
	 * @var string
	 * @ORM\ManyToMany(type="string", nullable=true)
	 */
	protected $accountName;

	/**
	 * @var string
	 * @ORM\ManyToMany(type="string", nullable=true)
	 */
	protected $accountIban;

	/**
	 * @var text
	 * @ORM\ManyToMany(type="text")
	 */
	protected $notes;


	public function __construct() {
		parent::__construct();
		$this->countries = new ArrayCollection();
	}


	/**
	 * @param Location\Location $country
	 * @return BankAccount
	 */
	public function addCountry(Location\Location  $country) {
		$this->countries->add($country);
		return $this;
	}


	/**
	 * @param Location\Location $country
	 * @return BankAccount
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
	 * @param Company $company
	 * @return BankAccount
	 */
	public function setCompany(Company  $company) {
		$this->company = $company;
		return $this;
	}


	/**
	 * @return Company
	 */
	public function getCompany() {
		return $this->company;
	}


	/**
	 * @param string $bankName
	 * @return BankAccount
	 */
	public function setBankName($bankName) {
		$this->bankName = $bankName;
		return $this;
	}


	/**
	 * @return string
	 */
	public function getBankName() {
		return $this->bankName;
	}


	/**
	 * @param address $bankAddress
	 * @return BankAccount
	 */
	public function setBankAddress($bankAddress) {
		$this->bankAddress = $bankAddress;
		return $this;
	}


	/**
	 * @return address
	 */
	public function getBankAddress() {
		return $this->bankAddress;
	}


	/**
	 * @param string $bankSwift
	 * @return BankAccount
	 */
	public function setBankSwift($bankSwift) {
		$this->bankSwift = $bankSwift;
		return $this;
	}


	/**
	 * @return string
	 */
	public function getBankSwift() {
		return $this->bankSwift;
	}


	/**
	 * @param string $accountNumber
	 * @return BankAccount
	 */
	public function setAccountNumber($accountNumber) {
		$this->accountNumber = $accountNumber;
		return $this;
	}


	/**
	 * @return string
	 */
	public function getAccountNumber() {
		return $this->accountNumber;
	}


	/**
	 * @param string $accountName
	 * @return BankAccount
	 */
	public function setAccountName($accountName) {
		$this->accountName = $accountName;
		return $this;
	}


	/**
	 * @return string
	 */
	public function getAccountName() {
		return $this->accountName;
	}


	/**
	 * @param string $accountIban
	 * @return BankAccount
	 */
	public function setAccountIban($accountIban) {
		$this->accountIban = $accountIban;
		return $this;
	}


	/**
	 * @return string
	 */
	public function getAccountIban() {
		return $this->accountIban;
	}


	/**
	 * @param text $notes
	 * @return BankAccount
	 */
	public function setNotes($notes) {
		$this->notes = $notes;
		return $this;
	}


	/**
	 * @return text
	 */
	public function getNotes() {
		return $this->notes;
	}

}
