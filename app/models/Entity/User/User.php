<?php

namespace Entity\User;

use Entity\Contact;
use Entity\Dictionary;
use Entity\Location;
use Entity\Rental;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="user_user")
 */
class User extends \Entity\BaseEntityDetails {

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $login;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $password;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Role", mappedBy="users", cascade={"persist"})
	 */
	protected $roles;

	/**
	 * @var Collection
	 * @ORM\OneToMany(targetEntity="Entity\Contact\Contact", mappedBy="user")
	 */
	protected $contacts;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entity\Dictionary\Language")
	 */
	protected $defaultLanguage;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Entity\Location\Location", mappedBy="users")
	 */
	protected $locations;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Entity\Rental\Type", mappedBy="users")
	 */
	protected $rentalTypes;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $invoicingSalutation;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $invoicingFirstName;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $invoicingLastName;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $invoicingCompanyName;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entity\Contact\Contact")
	 */
	protected $invoicingEmail;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entity\Contact\Contact")
	 */
	protected $invoicingPhone;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entity\Contact\Contact")
	 */
	protected $invoicingUrl;

	/**
	 * @var address
	 * @ORM\Column(type="address", nullable=true)
	 */
	protected $invoicingAddress;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $invoicingCompanyId;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $invoicingCompanyVatId;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="User")
	 */
	protected $currentTelmarkOperator;

	/**
	 * @var Collection
	 * @ORM\OneToMany(targetEntity="Combination", mappedBy="user", cascade={"persist", "remove"})
	 */
	protected $combinations;

	/**
	 * @var Collection
	 * @ORM\OneToMany(targetEntity="Entity\Rental\Rental", mappedBy="user", cascade={"persist"})
	 */
	protected $rentals;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Entity\Autopilot\Task", inversedBy="usersExcluded")
	 */
	protected $tasks;

	/* ----------------------------- Methods ----------------------------- */


	public function __construct() {
		parent::__construct();

		$this->roles = new \Doctrine\Common\Collections\ArrayCollection;
		$this->contacts = new \Doctrine\Common\Collections\ArrayCollection;
		$this->locations = new \Doctrine\Common\Collections\ArrayCollection;
		$this->rentalTypes = new \Doctrine\Common\Collections\ArrayCollection;
		$this->combinations = new \Doctrine\Common\Collections\ArrayCollection;
		$this->rentals = new \Doctrine\Common\Collections\ArrayCollection;
		$this->tasks = new \Doctrine\Common\Collections\ArrayCollection;
	}

	/**
	 * @param string
	 * @return \Entity\User\User
	 */
	public function setLogin($login) {
		$this->login = $login;

		return $this;
	}

	/**
	 * @return \Entity\User\User
	 */
	public function unsetLogin() {
		$this->login = NULL;

		return $this;
	}

	/**
	 * @return string|NULL
	 */
	public function getLogin() {
		return $this->login;
	}

	/**
	 * @param string
	 * @return \Entity\User\User
	 */
	public function setPassword($password) {
		$this->password = $password;

		return $this;
	}

	/**
	 * @return \Entity\User\User
	 */
	public function unsetPassword() {
		$this->password = NULL;

		return $this;
	}

	/**
	 * @return string|NULL
	 */
	public function getPassword() {
		return $this->password;
	}

	/**
	 * @param \Entity\User\Role
	 * @return \Entity\User\User
	 */
	public function addRole(\Entity\User\Role $role) {
		if(!$this->roles->contains($role)) {
			$this->roles->add($role);
		}
		$role->addUser($this);

		return $this;
	}

	/**
	 * @param \Entity\User\Role
	 * @return \Entity\User\User
	 */
	public function removeRole(\Entity\User\Role $role) {
		if($this->roles->contains($role)) {
			$this->roles->removeElement($role);
		}
		$role->removeUser($this);

		return $this;
	}

	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection of \Entity\User\Role
	 */
	public function getRoles() {
		return $this->roles;
	}

	/**
	 * @param \Entity\Contact\Contact
	 * @return \Entity\User\User
	 */
	public function addContact(\Entity\Contact\Contact $contact) {
		if(!$this->contacts->contains($contact)) {
			$this->contacts->add($contact);
		}
		$contact->addUser($this);

		return $this;
	}

	/**
	 * @param \Entity\Contact\Contact
	 * @return \Entity\User\User
	 */
	public function removeContact(\Entity\Contact\Contact $contact) {
		if($this->contacts->contains($contact)) {
			$this->contacts->removeElement($contact);
		}
		$contact->removeUser($this);

		return $this;
	}

	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection of \Entity\Contact\Contact
	 */
	public function getContacts() {
		return $this->contacts;
	}

	/**
	 * @param \Entity\Dictionary\Language
	 * @return \Entity\User\User
	 */
	public function setDefaultLanguage(\Entity\Dictionary\Language $defaultLanguage) {
		$this->defaultLanguage = $defaultLanguage;

		return $this;
	}

	/**
	 * @return \Entity\User\User
	 */
	public function unsetDefaultLanguage() {
		$this->defaultLanguage = NULL;

		return $this;
	}

	/**
	 * @return \Entity\Dictionary\Language|NULL
	 */
	public function getDefaultLanguage() {
		return $this->defaultLanguage;
	}

	/**
	 * @param \Entity\Location\Location
	 * @return \Entity\User\User
	 */
	public function addLocation(\Entity\Location\Location $location) {
		if(!$this->locations->contains($location)) {
			$this->locations->add($location);
		}
		$location->addUser($this);

		return $this;
	}

	/**
	 * @param \Entity\Location\Location
	 * @return \Entity\User\User
	 */
	public function removeLocation(\Entity\Location\Location $location) {
		if($this->locations->contains($location)) {
			$this->locations->removeElement($location);
		}
		$location->removeUser($this);

		return $this;
	}

	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection of \Entity\Location\Location
	 */
	public function getLocations() {
		return $this->locations;
	}

	/**
	 * @param \Entity\Rental\Type
	 * @return \Entity\User\User
	 */
	public function addRentalType(\Entity\Rental\Type $rentalType) {
		if(!$this->rentalTypes->contains($rentalType)) {
			$this->rentalTypes->add($rentalType);
		}
		$rentalType->addUser($this);

		return $this;
	}

	/**
	 * @param \Entity\Rental\Type
	 * @return \Entity\User\User
	 */
	public function removeRentalType(\Entity\Rental\Type $rentalType) {
		if($this->rentalTypes->contains($rentalType)) {
			$this->rentalTypes->removeElement($rentalType);
		}
		$rentalType->removeUser($this);

		return $this;
	}

	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection of \Entity\Rental\Type
	 */
	public function getRentalTypes() {
		return $this->rentalTypes;
	}

	/**
	 * @param string
	 * @return \Entity\User\User
	 */
	public function setInvoicingSalutation($invoicingSalutation) {
		$this->invoicingSalutation = $invoicingSalutation;

		return $this;
	}

	/**
	 * @return \Entity\User\User
	 */
	public function unsetInvoicingSalutation() {
		$this->invoicingSalutation = NULL;

		return $this;
	}

	/**
	 * @return string|NULL
	 */
	public function getInvoicingSalutation() {
		return $this->invoicingSalutation;
	}

	/**
	 * @param string
	 * @return \Entity\User\User
	 */
	public function setInvoicingFirstName($invoicingFirstName) {
		$this->invoicingFirstName = $invoicingFirstName;

		return $this;
	}

	/**
	 * @return \Entity\User\User
	 */
	public function unsetInvoicingFirstName() {
		$this->invoicingFirstName = NULL;

		return $this;
	}

	/**
	 * @return string|NULL
	 */
	public function getInvoicingFirstName() {
		return $this->invoicingFirstName;
	}

	/**
	 * @param string
	 * @return \Entity\User\User
	 */
	public function setInvoicingLastName($invoicingLastName) {
		$this->invoicingLastName = $invoicingLastName;

		return $this;
	}

	/**
	 * @return \Entity\User\User
	 */
	public function unsetInvoicingLastName() {
		$this->invoicingLastName = NULL;

		return $this;
	}

	/**
	 * @return string|NULL
	 */
	public function getInvoicingLastName() {
		return $this->invoicingLastName;
	}

	/**
	 * @param string
	 * @return \Entity\User\User
	 */
	public function setInvoicingCompanyName($invoicingCompanyName) {
		$this->invoicingCompanyName = $invoicingCompanyName;

		return $this;
	}

	/**
	 * @return \Entity\User\User
	 */
	public function unsetInvoicingCompanyName() {
		$this->invoicingCompanyName = NULL;

		return $this;
	}

	/**
	 * @return string|NULL
	 */
	public function getInvoicingCompanyName() {
		return $this->invoicingCompanyName;
	}

	/**
	 * @param \Entity\Contact\Contact
	 * @return \Entity\User\User
	 */
	public function setInvoicingEmail(\Entity\Contact\Contact $invoicingEmail) {
		$this->invoicingEmail = $invoicingEmail;

		return $this;
	}

	/**
	 * @return \Entity\User\User
	 */
	public function unsetInvoicingEmail() {
		$this->invoicingEmail = NULL;

		return $this;
	}

	/**
	 * @return \Entity\Contact\Contact|NULL
	 */
	public function getInvoicingEmail() {
		return $this->invoicingEmail;
	}

	/**
	 * @param \Entity\Contact\Contact
	 * @return \Entity\User\User
	 */
	public function setInvoicingPhone(\Entity\Contact\Contact $invoicingPhone) {
		$this->invoicingPhone = $invoicingPhone;

		return $this;
	}

	/**
	 * @return \Entity\User\User
	 */
	public function unsetInvoicingPhone() {
		$this->invoicingPhone = NULL;

		return $this;
	}

	/**
	 * @return \Entity\Contact\Contact|NULL
	 */
	public function getInvoicingPhone() {
		return $this->invoicingPhone;
	}

	/**
	 * @param \Entity\Contact\Contact
	 * @return \Entity\User\User
	 */
	public function setInvoicingUrl(\Entity\Contact\Contact $invoicingUrl) {
		$this->invoicingUrl = $invoicingUrl;

		return $this;
	}

	/**
	 * @return \Entity\User\User
	 */
	public function unsetInvoicingUrl() {
		$this->invoicingUrl = NULL;

		return $this;
	}

	/**
	 * @return \Entity\Contact\Contact|NULL
	 */
	public function getInvoicingUrl() {
		return $this->invoicingUrl;
	}

	/**
	 * @param \Extras\Types\Address
	 * @return \Entity\User\User
	 */
	public function setInvoicingAddress(\Extras\Types\Address $invoicingAddress) {
		$this->invoicingAddress = $invoicingAddress;

		return $this;
	}

	/**
	 * @return \Entity\User\User
	 */
	public function unsetInvoicingAddress() {
		$this->invoicingAddress = NULL;

		return $this;
	}

	/**
	 * @return \Extras\Types\Address|NULL
	 */
	public function getInvoicingAddress() {
		return $this->invoicingAddress;
	}

	/**
	 * @param string
	 * @return \Entity\User\User
	 */
	public function setInvoicingCompanyId($invoicingCompanyId) {
		$this->invoicingCompanyId = $invoicingCompanyId;

		return $this;
	}

	/**
	 * @return \Entity\User\User
	 */
	public function unsetInvoicingCompanyId() {
		$this->invoicingCompanyId = NULL;

		return $this;
	}

	/**
	 * @return string|NULL
	 */
	public function getInvoicingCompanyId() {
		return $this->invoicingCompanyId;
	}

	/**
	 * @param string
	 * @return \Entity\User\User
	 */
	public function setInvoicingCompanyVatId($invoicingCompanyVatId) {
		$this->invoicingCompanyVatId = $invoicingCompanyVatId;

		return $this;
	}

	/**
	 * @return \Entity\User\User
	 */
	public function unsetInvoicingCompanyVatId() {
		$this->invoicingCompanyVatId = NULL;

		return $this;
	}

	/**
	 * @return string|NULL
	 */
	public function getInvoicingCompanyVatId() {
		return $this->invoicingCompanyVatId;
	}

	/**
	 * @param \Entity\User\User
	 * @return \Entity\User\User
	 */
	public function setCurrentTelmarkOperator(\Entity\User\User $currentTelmarkOperator) {
		$this->currentTelmarkOperator = $currentTelmarkOperator;

		return $this;
	}

	/**
	 * @return \Entity\User\User
	 */
	public function unsetCurrentTelmarkOperator() {
		$this->currentTelmarkOperator = NULL;

		return $this;
	}

	/**
	 * @return \Entity\User\User|NULL
	 */
	public function getCurrentTelmarkOperator() {
		return $this->currentTelmarkOperator;
	}

	/**
	 * @param json
	 * @return \Entity\User\User
	 */
	public function setAttributes($attributes) {
		$this->attributes = $attributes;

		return $this;
	}

	/**
	 * @return json|NULL
	 */
	public function getAttributes() {
		return $this->attributes;
	}

	/**
	 * @param \Entity\User\Combination
	 * @return \Entity\User\User
	 */
	public function addCombination(\Entity\User\Combination $combination) {
		if(!$this->combinations->contains($combination)) {
			$this->combinations->add($combination);
		}
		$combination->setUser($this);

		return $this;
	}

	/**
	 * @param \Entity\User\Combination
	 * @return \Entity\User\User
	 */
	public function removeCombination(\Entity\User\Combination $combination) {
		if($this->combinations->contains($combination)) {
			$this->combinations->removeElement($combination);
		}
		$combination->removeUser($this);

		return $this;
	}

	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection of \Entity\User\Combination
	 */
	public function getCombinations() {
		return $this->combinations;
	}

	/**
	 * @param \Entity\Rental\Rental
	 * @return \Entity\User\User
	 */
	public function addRental(\Entity\Rental\Rental $rental) {
		if(!$this->rentals->contains($rental)) {
			$this->rentals->add($rental);
		}
		$rental->addUser($this);

		return $this;
	}

	/**
	 * @param \Entity\Rental\Rental
	 * @return \Entity\User\User
	 */
	public function removeRental(\Entity\Rental\Rental $rental) {
		if($this->rentals->contains($rental)) {
			$this->rentals->removeElement($rental);
		}
		$rental->removeUser($this);

		return $this;
	}

	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection of \Entity\Rental\Rental
	 */
	public function getRentals() {
		return $this->rentals;
	}

	/**
	 * @param \Entity\Autopilot\Task
	 * @return \Entity\User\User
	 */
	public function addTask(\Entity\Autopilot\Task $task) {
		if(!$this->tasks->contains($task)) {
			$this->tasks->add($task);
		}

		return $this;
	}

	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection of \Entity\Autopilot\Task
	 */
	public function getTasks() {
		return $this->tasks;
	}
	
}