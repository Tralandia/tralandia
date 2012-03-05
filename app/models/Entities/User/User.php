<?php

namespace User;

use Contact;
use Dictionary;
use Location;
use Rental;
use User;
use Doctrine\Common\Collections\Collection
use Doctrine\Common\Collections\ArrayCollection


/**
 * @Entity()
 * @Table(name="user_user")
 */
class User extends \BaseEntity {

	/**
	 * @var string
	 * @Column(type="string", nullable=true)
	 */
	protected $login;

	/**
	 * @var string
	 * @Column(type="string", nullable=true)
	 */
	protected $password;

	/**
	 * @var Collection
	 * @Column(type="Role")
	 */
	protected $roles;

	/**
	 * @var Collection
	 * @ManyToMany(targetEntity="Contact\Contact")
	 */
	protected $contacts;

	/**
	 * @var Collection
	 * @ManyToMany(targetEntity="Dictionary\Language")
	 */
	protected $languageDefault;

	/**
	 * @var Collection
	 * @ManyToMany(targetEntity="Dictionary\Language")
	 */
	protected $languagesPasive;

	/**
	 * @var Collection
	 * @ManyToMany(targetEntity="Dictionary\Language")
	 */
	protected $languagesActive;

	/**
	 * @var Collection
	 * @ManyToMany(targetEntity="Dictionary\Language")
	 */
	protected $languagesNative;

	/**
	 * @var Collection
	 * @ManyToMany(targetEntity="Location\Location")
	 */
	protected $locations;

	/**
	 * @var Collection
	 * @ManyToMany(targetEntity="Rental\Type")
	 */
	protected $rentalTypes;

	/**
	 * @var string
	 * @Column(type="string", nullable=true)
	 */
	protected $invoicingSalutation;

	/**
	 * @var string
	 * @Column(type="string", nullable=true)
	 */
	protected $invoicingFirstName;

	/**
	 * @var string
	 * @Column(type="string", nullable=true)
	 */
	protected $invoicingLastName;

	/**
	 * @var string
	 * @Column(type="string", nullable=true)
	 */
	protected $invoicingCompanyName;

	/**
	 * @var Collection
	 * @ManyToMany(targetEntity="Contact\Contact")
	 */
	protected $invoicingEmail;

	/**
	 * @var Collection
	 * @ManyToMany(targetEntity="Contact\Contact")
	 */
	protected $invoicingPhone;

	/**
	 * @var Collection
	 * @ManyToMany(targetEntity="Contact\Contact")
	 */
	protected $invoicingUrl;

	/**
	 * @var address
	 * @Column(type="address")
	 */
	protected $invoicingAddress;

	/**
	 * @var string
	 * @Column(type="string", nullable=true)
	 */
	protected $invoicingCompanyId;

	/**
	 * @var string
	 * @Column(type="string", nullable=true)
	 */
	protected $invoicingCompanyVatId;

	/**
	 * @var Collection
	 * @Column(type="User")
	 */
	protected $telmarkCurrentOperator;

	/**
	 * @var json
	 * @Column(type="json")
	 */
	protected $attributes;

	/**
	 * @var Collection
	 * @Column(type="Combination")
	 */
	protected $combinations;


	public function __construct() {

	}


	/**
	 * @param string $login
	 * @return User
	 */
	public function setLogin($login) {
		$this->login = $login;
		return $this;
	}


	/**
	 * @return string
	 */
	public function getLogin() {
		return $this->login;
	}


	/**
	 * @param string $password
	 * @return User
	 */
	public function setPassword($password) {
		$this->password = $password;
		return $this;
	}


	/**
	 * @return string
	 */
	public function getPassword() {
		return $this->password;
	}


	/**
	 * @param Role $roles
	 * @return User
	 */
	public function setRoles(Role  $roles) {
		$this->roles = $roles;
		return $this;
	}


	/**
	 * @return Role
	 */
	public function getRoles() {
		return $this->roles;
	}


	/**
	 * @param Contact\Contact $contacts
	 * @return User
	 */
	public function setContacts(Contact\Contact  $contacts) {
		$this->contacts = $contacts;
		return $this;
	}


	/**
	 * @return Contact\Contact
	 */
	public function getContacts() {
		return $this->contacts;
	}


	/**
	 * @param Dictionary\Language $languageDefault
	 * @return User
	 */
	public function setLanguageDefault(Dictionary\Language  $languageDefault) {
		$this->languageDefault = $languageDefault;
		return $this;
	}


	/**
	 * @return Dictionary\Language
	 */
	public function getLanguageDefault() {
		return $this->languageDefault;
	}


	/**
	 * @param Dictionary\Language $languagesPasive
	 * @return User
	 */
	public function setLanguagesPasive(Dictionary\Language  $languagesPasive) {
		$this->languagesPasive = $languagesPasive;
		return $this;
	}


	/**
	 * @return Dictionary\Language
	 */
	public function getLanguagesPasive() {
		return $this->languagesPasive;
	}


	/**
	 * @param Dictionary\Language $languagesActive
	 * @return User
	 */
	public function setLanguagesActive(Dictionary\Language  $languagesActive) {
		$this->languagesActive = $languagesActive;
		return $this;
	}


	/**
	 * @return Dictionary\Language
	 */
	public function getLanguagesActive() {
		return $this->languagesActive;
	}


	/**
	 * @param Dictionary\Language $languagesNative
	 * @return User
	 */
	public function setLanguagesNative(Dictionary\Language  $languagesNative) {
		$this->languagesNative = $languagesNative;
		return $this;
	}


	/**
	 * @return Dictionary\Language
	 */
	public function getLanguagesNative() {
		return $this->languagesNative;
	}


	/**
	 * @param Location\Location $locations
	 * @return User
	 */
	public function setLocations(Location\Location  $locations) {
		$this->locations = $locations;
		return $this;
	}


	/**
	 * @return Location\Location
	 */
	public function getLocations() {
		return $this->locations;
	}


	/**
	 * @param Rental\Type $rentalTypes
	 * @return User
	 */
	public function setRentalTypes(Rental\Type  $rentalTypes) {
		$this->rentalTypes = $rentalTypes;
		return $this;
	}


	/**
	 * @return Rental\Type
	 */
	public function getRentalTypes() {
		return $this->rentalTypes;
	}


	/**
	 * @param string $invoicingSalutation
	 * @return User
	 */
	public function setInvoicingSalutation($invoicingSalutation) {
		$this->invoicingSalutation = $invoicingSalutation;
		return $this;
	}


	/**
	 * @return string
	 */
	public function getInvoicingSalutation() {
		return $this->invoicingSalutation;
	}


	/**
	 * @param string $invoicingFirstName
	 * @return User
	 */
	public function setInvoicingFirstName($invoicingFirstName) {
		$this->invoicingFirstName = $invoicingFirstName;
		return $this;
	}


	/**
	 * @return string
	 */
	public function getInvoicingFirstName() {
		return $this->invoicingFirstName;
	}


	/**
	 * @param string $invoicingLastName
	 * @return User
	 */
	public function setInvoicingLastName($invoicingLastName) {
		$this->invoicingLastName = $invoicingLastName;
		return $this;
	}


	/**
	 * @return string
	 */
	public function getInvoicingLastName() {
		return $this->invoicingLastName;
	}


	/**
	 * @param string $invoicingCompanyName
	 * @return User
	 */
	public function setInvoicingCompanyName($invoicingCompanyName) {
		$this->invoicingCompanyName = $invoicingCompanyName;
		return $this;
	}


	/**
	 * @return string
	 */
	public function getInvoicingCompanyName() {
		return $this->invoicingCompanyName;
	}


	/**
	 * @param Contact\Contact $invoicingEmail
	 * @return User
	 */
	public function setInvoicingEmail(Contact\Contact  $invoicingEmail) {
		$this->invoicingEmail = $invoicingEmail;
		return $this;
	}


	/**
	 * @return Contact\Contact
	 */
	public function getInvoicingEmail() {
		return $this->invoicingEmail;
	}


	/**
	 * @param Contact\Contact $invoicingPhone
	 * @return User
	 */
	public function setInvoicingPhone(Contact\Contact  $invoicingPhone) {
		$this->invoicingPhone = $invoicingPhone;
		return $this;
	}


	/**
	 * @return Contact\Contact
	 */
	public function getInvoicingPhone() {
		return $this->invoicingPhone;
	}


	/**
	 * @param Contact\Contact $invoicingUrl
	 * @return User
	 */
	public function setInvoicingUrl(Contact\Contact  $invoicingUrl) {
		$this->invoicingUrl = $invoicingUrl;
		return $this;
	}


	/**
	 * @return Contact\Contact
	 */
	public function getInvoicingUrl() {
		return $this->invoicingUrl;
	}


	/**
	 * @param address $invoicingAddress
	 * @return User
	 */
	public function setInvoicingAddress($invoicingAddress) {
		$this->invoicingAddress = $invoicingAddress;
		return $this;
	}


	/**
	 * @return address
	 */
	public function getInvoicingAddress() {
		return $this->invoicingAddress;
	}


	/**
	 * @param string $invoicingCompanyId
	 * @return User
	 */
	public function setInvoicingCompanyId($invoicingCompanyId) {
		$this->invoicingCompanyId = $invoicingCompanyId;
		return $this;
	}


	/**
	 * @return string
	 */
	public function getInvoicingCompanyId() {
		return $this->invoicingCompanyId;
	}


	/**
	 * @param string $invoicingCompanyVatId
	 * @return User
	 */
	public function setInvoicingCompanyVatId($invoicingCompanyVatId) {
		$this->invoicingCompanyVatId = $invoicingCompanyVatId;
		return $this;
	}


	/**
	 * @return string
	 */
	public function getInvoicingCompanyVatId() {
		return $this->invoicingCompanyVatId;
	}


	/**
	 * @param User $telmarkCurrentOperator
	 * @return User
	 */
	public function setTelmarkCurrentOperator(User  $telmarkCurrentOperator) {
		$this->telmarkCurrentOperator = $telmarkCurrentOperator;
		return $this;
	}


	/**
	 * @return User
	 */
	public function getTelmarkCurrentOperator() {
		return $this->telmarkCurrentOperator;
	}


	/**
	 * @param json $attributes
	 * @return User
	 */
	public function setAttributes($attributes) {
		$this->attributes = $attributes;
		return $this;
	}


	/**
	 * @return json
	 */
	public function getAttributes() {
		return $this->attributes;
	}


	/**
	 * @param Combination $combinations
	 * @return User
	 */
	public function setCombinations(Combination  $combinations) {
		$this->combinations = $combinations;
		return $this;
	}


	/**
	 * @return Combination
	 */
	public function getCombinations() {
		return $this->combinations;
	}

}
