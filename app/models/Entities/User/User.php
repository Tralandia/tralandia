<?php

namespace Entities\User;

use Entities\Contact;
use Entities\Dictionary;
use Entities\Location;
use Entities\Rental;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="user_user")
 */
class User extends \Entities\BaseEntity {

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
	 * @ORM\ManyToMany(targetEntity="Role", mappedBy="users")
	 */
	protected $roles;

	/**
	 * @var Collection
	 * @ORM\OneToMany(targetEntity="Entities\Contact\Contact", mappedBy="user")
	 */
	protected $contacts;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entities\Dictionary\Language")
	 */
	protected $defaultLanguage;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Entities\Location\Location", mappedBy="users")
	 */
	protected $locations;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Entities\Rental\Type", mappedBy="users")
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
	 * @ORM\ManyToOne(targetEntity="Entities\Contact\Contact")
	 */
	protected $invoicingEmail;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entities\Contact\Contact")
	 */
	protected $invoicingPhone;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entities\Contact\Contact")
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
	 * @var json
	 * @ORM\Column(type="json")
	 */
	protected $attributes;

	/**
	 * @var Collection
	 * @ORM\OneToMany(targetEntity="Combination", mappedBy="user")
	 */
	protected $combinations;

	/**
	 * @var Collection
	 * @ORM\OneToMany(targetEntity="Entities\Rental\Rental", mappedBy="user")
	 */
	protected $rentals;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Entities\Autopilot\Task", inversedBy="usersExcluded")
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
	 * @return \Entities\User\User
	 */
	public function setLogin($login) {
		$this->login = $login;

		return $this;
	}

	/**
	 * @return \Entities\User\User
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
	 * @return \Entities\User\User
	 */
	public function setPassword($password) {
		$this->password = $password;

		return $this;
	}

	/**
	 * @return \Entities\User\User
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
	 * @param \Entities\User\Role
	 * @return \Entities\User\User
	 */
	public function addRole(\Entities\User\Role $role) {
		if(!$this->roles->contains($role)) {
			$this->roles->add($role);
		}
		$role->addUser($this);

		return $this;
	}

	/**
	 * @param \Entities\User\Role
	 * @return \Entities\User\User
	 */
	public function removeRole(\Entities\User\Role $role) {
		if($this->roles->contains($role)) {
			$this->roles->removeElement($role);
		}
		$role->removeUser($this);

		return $this;
	}

	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection of \Entities\User\Role
	 */
	public function getRoles() {
		return $this->roles;
	}

	/**
	 * @param \Entities\Contact\Contact
	 * @return \Entities\User\User
	 */
	public function addContact(\Entities\Contact\Contact $contact) {
		if(!$this->contacts->contains($contact)) {
			$this->contacts->add($contact);
		}
		$contact->addUser($this);

		return $this;
	}

	/**
	 * @param \Entities\Contact\Contact
	 * @return \Entities\User\User
	 */
	public function removeContact(\Entities\Contact\Contact $contact) {
		if($this->contacts->contains($contact)) {
			$this->contacts->removeElement($contact);
		}
		$contact->removeUser($this);

		return $this;
	}

	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection of \Entities\Contact\Contact
	 */
	public function getContacts() {
		return $this->contacts;
	}

	/**
	 * @param \Entities\Dictionary\Language
	 * @return \Entities\User\User
	 */
	public function setDefaultLanguage(\Entities\Dictionary\Language $defaultLanguage) {
		$this->defaultLanguage = $defaultLanguage;

		return $this;
	}

	/**
	 * @return \Entities\User\User
	 */
	public function unsetDefaultLanguage() {
		$this->defaultLanguage = NULL;

		return $this;
	}

	/**
	 * @return \Entities\Dictionary\Language|NULL
	 */
	public function getDefaultLanguage() {
		return $this->defaultLanguage;
	}

	/**
	 * @param \Entities\Location\Location
	 * @return \Entities\User\User
	 */
	public function addLocation(\Entities\Location\Location $location) {
		if(!$this->locations->contains($location)) {
			$this->locations->add($location);
		}
		$location->addUser($this);

		return $this;
	}

	/**
	 * @param \Entities\Location\Location
	 * @return \Entities\User\User
	 */
	public function removeLocation(\Entities\Location\Location $location) {
		if($this->locations->contains($location)) {
			$this->locations->removeElement($location);
		}
		$location->removeUser($this);

		return $this;
	}

	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection of \Entities\Location\Location
	 */
	public function getLocations() {
		return $this->locations;
	}

	/**
	 * @param \Entities\Rental\Type
	 * @return \Entities\User\User
	 */
	public function addRentalType(\Entities\Rental\Type $rentalType) {
		if(!$this->rentalTypes->contains($rentalType)) {
			$this->rentalTypes->add($rentalType);
		}
		$rentalType->addUser($this);

		return $this;
	}

	/**
	 * @param \Entities\Rental\Type
	 * @return \Entities\User\User
	 */
	public function removeRentalType(\Entities\Rental\Type $rentalType) {
		if($this->rentalTypes->contains($rentalType)) {
			$this->rentalTypes->removeElement($rentalType);
		}
		$rentalType->removeUser($this);

		return $this;
	}

	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection of \Entities\Rental\Type
	 */
	public function getRentalTypes() {
		return $this->rentalTypes;
	}

	/**
	 * @param string
	 * @return \Entities\User\User
	 */
	public function setInvoicingSalutation($invoicingSalutation) {
		$this->invoicingSalutation = $invoicingSalutation;

		return $this;
	}

	/**
	 * @return \Entities\User\User
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
	 * @return \Entities\User\User
	 */
	public function setInvoicingFirstName($invoicingFirstName) {
		$this->invoicingFirstName = $invoicingFirstName;

		return $this;
	}

	/**
	 * @return \Entities\User\User
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
	 * @return \Entities\User\User
	 */
	public function setInvoicingLastName($invoicingLastName) {
		$this->invoicingLastName = $invoicingLastName;

		return $this;
	}

	/**
	 * @return \Entities\User\User
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
	 * @return \Entities\User\User
	 */
	public function setInvoicingCompanyName($invoicingCompanyName) {
		$this->invoicingCompanyName = $invoicingCompanyName;

		return $this;
	}

	/**
	 * @return \Entities\User\User
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
	 * @param \Entities\Contact\Contact
	 * @return \Entities\User\User
	 */
	public function setInvoicingEmail(\Entities\Contact\Contact $invoicingEmail) {
		$this->invoicingEmail = $invoicingEmail;

		return $this;
	}

	/**
	 * @return \Entities\User\User
	 */
	public function unsetInvoicingEmail() {
		$this->invoicingEmail = NULL;

		return $this;
	}

	/**
	 * @return \Entities\Contact\Contact|NULL
	 */
	public function getInvoicingEmail() {
		return $this->invoicingEmail;
	}

	/**
	 * @param \Entities\Contact\Contact
	 * @return \Entities\User\User
	 */
	public function setInvoicingPhone(\Entities\Contact\Contact $invoicingPhone) {
		$this->invoicingPhone = $invoicingPhone;

		return $this;
	}

	/**
	 * @return \Entities\User\User
	 */
	public function unsetInvoicingPhone() {
		$this->invoicingPhone = NULL;

		return $this;
	}

	/**
	 * @return \Entities\Contact\Contact|NULL
	 */
	public function getInvoicingPhone() {
		return $this->invoicingPhone;
	}

	/**
	 * @param \Entities\Contact\Contact
	 * @return \Entities\User\User
	 */
	public function setInvoicingUrl(\Entities\Contact\Contact $invoicingUrl) {
		$this->invoicingUrl = $invoicingUrl;

		return $this;
	}

	/**
	 * @return \Entities\User\User
	 */
	public function unsetInvoicingUrl() {
		$this->invoicingUrl = NULL;

		return $this;
	}

	/**
	 * @return \Entities\Contact\Contact|NULL
	 */
	public function getInvoicingUrl() {
		return $this->invoicingUrl;
	}

	/**
	 * @param \Extras\Types\Address
	 * @return \Entities\User\User
	 */
	public function setInvoicingAddress(\Extras\Types\Address $invoicingAddress) {
		$this->invoicingAddress = $invoicingAddress;

		return $this;
	}

	/**
	 * @return \Entities\User\User
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
	 * @return \Entities\User\User
	 */
	public function setInvoicingCompanyId($invoicingCompanyId) {
		$this->invoicingCompanyId = $invoicingCompanyId;

		return $this;
	}

	/**
	 * @return \Entities\User\User
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
	 * @return \Entities\User\User
	 */
	public function setInvoicingCompanyVatId($invoicingCompanyVatId) {
		$this->invoicingCompanyVatId = $invoicingCompanyVatId;

		return $this;
	}

	/**
	 * @return \Entities\User\User
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
	 * @param \Entities\User\User
	 * @return \Entities\User\User
	 */
	public function setCurrentTelmarkOperator(\Entities\User\User $currentTelmarkOperator) {
		$this->currentTelmarkOperator = $currentTelmarkOperator;

		return $this;
	}

	/**
	 * @return \Entities\User\User
	 */
	public function unsetCurrentTelmarkOperator() {
		$this->currentTelmarkOperator = NULL;

		return $this;
	}

	/**
	 * @return \Entities\User\User|NULL
	 */
	public function getCurrentTelmarkOperator() {
		return $this->currentTelmarkOperator;
	}

	/**
	 * @param json
	 * @return \Entities\User\User
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
	 * @param \Entities\User\Combination
	 * @return \Entities\User\User
	 */
	public function addCombination(\Entities\User\Combination $combination) {
		if(!$this->combinations->contains($combination)) {
			$this->combinations->add($combination);
		}
		$combination->addUser($this);

		return $this;
	}

	/**
	 * @param \Entities\User\Combination
	 * @return \Entities\User\User
	 */
	public function removeCombination(\Entities\User\Combination $combination) {
		if($this->combinations->contains($combination)) {
			$this->combinations->removeElement($combination);
		}
		$combination->removeUser($this);

		return $this;
	}

	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection of \Entities\User\Combination
	 */
	public function getCombinations() {
		return $this->combinations;
	}

	/**
	 * @param \Entities\Rental\Rental
	 * @return \Entities\User\User
	 */
	public function addRental(\Entities\Rental\Rental $rental) {
		if(!$this->rentals->contains($rental)) {
			$this->rentals->add($rental);
		}
		$rental->addUser($this);

		return $this;
	}

	/**
	 * @param \Entities\Rental\Rental
	 * @return \Entities\User\User
	 */
	public function removeRental(\Entities\Rental\Rental $rental) {
		if($this->rentals->contains($rental)) {
			$this->rentals->removeElement($rental);
		}
		$rental->removeUser($this);

		return $this;
	}

	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection of \Entities\Rental\Rental
	 */
	public function getRentals() {
		return $this->rentals;
	}

	/**
	 * @param \Entities\Autopilot\Task
	 * @return \Entities\User\User
	 */
	public function addTask(\Entities\Autopilot\Task $task) {
		if(!$this->tasks->contains($task)) {
			$this->tasks->add($task);
		}

		return $this;
	}

	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection of \Entities\Autopilot\Task
	 */
	public function getTasks() {
		return $this->tasks;
	}
	
}