<?php

namespace Entity\User;

use Entity\Phrase;
use Entity\Location;
use Entity\Rental;
use Doctrine\ORM\Mapping as ORM;
use	Extras\Annotation as EA;


/**
 * @ORM\Entity()
 * @ORM\Table(name="user", indexes={@ORM\index(name="login", columns={"login"}), @ORM\index(name="password", columns={"password"})})
 * @EA\Primary(key="id", value="login")
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
	 * @ORM\ManyToOne(targetEntity="Role", inversedBy="users", cascade={"persist"})
	 * @EA\SingularName(name="role")
	 */
	protected $role;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entity\Location\Location")
	 */
	protected $primaryLocation;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $clientLocality;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $clientPostalCode;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Entity\Contact\Phone", mappedBy="rentals")
	 */
	protected $phones;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Entity\Contact\Email", mappedBy="rentals")
	 */
	protected $emails;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Entity\Contact\Url", mappedBy="rentals")
	 */
	protected $urls;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entity\Language")
	 */
	protected $language;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entity\Location\Location", inversedBy="users")
	 * @EA\SingularName(name="location")
	 */
	protected $location;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Entity\Rental\Type", mappedBy="users")
	 * @EA\SingularName(name="rentalType") 
	 */
	protected $rentalTypes;

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Entity\Invoice\InvoicingData", cascade={"persist", "remove"})
	 */
	protected $invoicingData;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="User")
	 */
	protected $currentTelmarkOperator;

	/**
	 * @var Collection
	 * @ORM\OneToMany(targetEntity="Combination", mappedBy="user", cascade={"persist", "remove"})
	 * @EA\SingularName(name="combination") 
	 */
	protected $combinations;

	/**
	 * @var Collection
	 * @ORM\OneToMany(targetEntity="Entity\Rental\Rental", mappedBy="user", cascade={"persist"})
	 * @EA\SingularName(name="rental") 
	 */
	protected $rentals;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Entity\Task\Task", inversedBy="usersExcluded")
	 * @EA\SingularName(name="task") 
	 */
	protected $tasks;

	/**
	 * @var boolean
	 * @ORM\Column(type="boolean", nullable=true)
	 */
	protected $subscribed;

	/**
	 * @var boolean
	 * @ORM\Column(type="boolean", nullable=true)
	 */
	protected $banned;

	/**
	 * @var boolean
	 * @ORM\Column(type="boolean", nullable=true)
	 */
	protected $full;

	/**
	 * @var boolean
	 * @ORM\Column(type="boolean", nullable=true)
	 */
	protected $spam;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Entity\Ticket\Message", inversedBy="toCc")
	 */
	protected $ticketMessages;


	//@entity-generator-code --- NEMAZAT !!!

	/* ----------------------------- Methods ----------------------------- */		
	public function __construct()
	{
		parent::__construct();

		$this->phones = new \Doctrine\Common\Collections\ArrayCollection;
		$this->emails = new \Doctrine\Common\Collections\ArrayCollection;
		$this->urls = new \Doctrine\Common\Collections\ArrayCollection;
		$this->rentalTypes = new \Doctrine\Common\Collections\ArrayCollection;
		$this->combinations = new \Doctrine\Common\Collections\ArrayCollection;
		$this->rentals = new \Doctrine\Common\Collections\ArrayCollection;
		$this->tasks = new \Doctrine\Common\Collections\ArrayCollection;
		$this->ticketMessages = new \Doctrine\Common\Collections\ArrayCollection;
	}
		
	/**
	 * @param string
	 * @return \Entity\User\User
	 */
	public function setLogin($login)
	{
		$this->login = $login;

		return $this;
	}
		
	/**
	 * @return \Entity\User\User
	 */
	public function unsetLogin()
	{
		$this->login = NULL;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getLogin()
	{
		return $this->login;
	}
		
	/**
	 * @param string
	 * @return \Entity\User\User
	 */
	public function setPassword($password)
	{
		$this->password = $password;

		return $this;
	}
		
	/**
	 * @return \Entity\User\User
	 */
	public function unsetPassword()
	{
		$this->password = NULL;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getPassword()
	{
		return $this->password;
	}
		
	/**
	 * @param \Entity\User\Role
	 * @return \Entity\User\User
	 */
	public function setRole(\Entity\User\Role $role)
	{
		$this->role = $role;

		return $this;
	}
		
	/**
	 * @return \Entity\User\User
	 */
	public function unsetRole()
	{
		$this->role = NULL;

		return $this;
	}
		
	/**
	 * @return \Entity\User\Role|NULL
	 */
	public function getRole()
	{
		return $this->role;
	}
		
	/**
	 * @param \Entity\Location\Location
	 * @return \Entity\User\User
	 */
	public function setPrimaryLocation(\Entity\Location\Location $primaryLocation)
	{
		$this->primaryLocation = $primaryLocation;

		return $this;
	}
		
	/**
	 * @return \Entity\User\User
	 */
	public function unsetPrimaryLocation()
	{
		$this->primaryLocation = NULL;

		return $this;
	}
		
	/**
	 * @return \Entity\Location\Location|NULL
	 */
	public function getPrimaryLocation()
	{
		return $this->primaryLocation;
	}
		
	/**
	 * @param string
	 * @return \Entity\User\User
	 */
	public function setClientLocality($clientLocality)
	{
		$this->clientLocality = $clientLocality;

		return $this;
	}
		
	/**
	 * @return \Entity\User\User
	 */
	public function unsetClientLocality()
	{
		$this->clientLocality = NULL;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getClientLocality()
	{
		return $this->clientLocality;
	}
		
	/**
	 * @param string
	 * @return \Entity\User\User
	 */
	public function setClientPostalCode($clientPostalCode)
	{
		$this->clientPostalCode = $clientPostalCode;

		return $this;
	}
		
	/**
	 * @return \Entity\User\User
	 */
	public function unsetClientPostalCode()
	{
		$this->clientPostalCode = NULL;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getClientPostalCode()
	{
		return $this->clientPostalCode;
	}
		
	/**
	 * @param \Entity\Contact\Phone
	 * @return \Entity\User\User
	 */
	public function addPhone(\Entity\Contact\Phone $phone)
	{
		if(!$this->phones->contains($phone)) {
			$this->phones->add($phone);
		}
		$phone->addRental($this);

		return $this;
	}
		
	/**
	 * @param \Entity\Contact\Phone
	 * @return \Entity\User\User
	 */
	public function removePhone(\Entity\Contact\Phone $phone)
	{
		if($this->phones->contains($phone)) {
			$this->phones->removeElement($phone);
		}
		$phone->removeRental($this);

		return $this;
	}
		
	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection of \Entity\Contact\Phone
	 */
	public function getPhones()
	{
		return $this->phones;
	}
		
	/**
	 * @param \Entity\Contact\Email
	 * @return \Entity\User\User
	 */
	public function addEmail(\Entity\Contact\Email $email)
	{
		if(!$this->emails->contains($email)) {
			$this->emails->add($email);
		}
		$email->addRental($this);

		return $this;
	}
		
	/**
	 * @param \Entity\Contact\Email
	 * @return \Entity\User\User
	 */
	public function removeEmail(\Entity\Contact\Email $email)
	{
		if($this->emails->contains($email)) {
			$this->emails->removeElement($email);
		}
		$email->removeRental($this);

		return $this;
	}
		
	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection of \Entity\Contact\Email
	 */
	public function getEmails()
	{
		return $this->emails;
	}
		
	/**
	 * @param \Entity\Contact\Url
	 * @return \Entity\User\User
	 */
	public function addUrl(\Entity\Contact\Url $url)
	{
		if(!$this->urls->contains($url)) {
			$this->urls->add($url);
		}
		$url->addRental($this);

		return $this;
	}
		
	/**
	 * @param \Entity\Contact\Url
	 * @return \Entity\User\User
	 */
	public function removeUrl(\Entity\Contact\Url $url)
	{
		if($this->urls->contains($url)) {
			$this->urls->removeElement($url);
		}
		$url->removeRental($this);

		return $this;
	}
		
	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection of \Entity\Contact\Url
	 */
	public function getUrls()
	{
		return $this->urls;
	}
		
	/**
	 * @param \Entity\Language
	 * @return \Entity\User\User
	 */
	public function setLanguage(\Entity\Language $language)
	{
		$this->language = $language;

		return $this;
	}
		
	/**
	 * @return \Entity\User\User
	 */
	public function unsetLanguage()
	{
		$this->language = NULL;

		return $this;
	}
		
	/**
	 * @return \Entity\Language|NULL
	 */
	public function getLanguage()
	{
		return $this->language;
	}
		
	/**
	 * @param \Entity\Location\Location
	 * @return \Entity\User\User
	 */
	public function setLocation(\Entity\Location\Location $location)
	{
		$this->location = $location;

		return $this;
	}
		
	/**
	 * @return \Entity\User\User
	 */
	public function unsetLocation()
	{
		$this->location = NULL;

		return $this;
	}
		
	/**
	 * @return \Entity\Location\Location|NULL
	 */
	public function getLocation()
	{
		return $this->location;
	}
		
	/**
	 * @param \Entity\Rental\Type
	 * @return \Entity\User\User
	 */
	public function addRentalType(\Entity\Rental\Type $rentalType)
	{
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
	public function removeRentalType(\Entity\Rental\Type $rentalType)
	{
		if($this->rentalTypes->contains($rentalType)) {
			$this->rentalTypes->removeElement($rentalType);
		}
		$rentalType->removeUser($this);

		return $this;
	}
		
	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection of \Entity\Rental\Type
	 */
	public function getRentalTypes()
	{
		return $this->rentalTypes;
	}
		
	/**
	 * @param \Entity\Invoice\InvoicingData
	 * @return \Entity\User\User
	 */
	public function setInvoicingData(\Entity\Invoice\InvoicingData $invoicingData)
	{
		$this->invoicingData = $invoicingData;

		return $this;
	}
		
	/**
	 * @return \Entity\Invoice\InvoicingData|NULL
	 */
	public function getInvoicingData()
	{
		return $this->invoicingData;
	}
		
	/**
	 * @param \Entity\User\User
	 * @return \Entity\User\User
	 */
	public function setCurrentTelmarkOperator(\Entity\User\User $currentTelmarkOperator)
	{
		$this->currentTelmarkOperator = $currentTelmarkOperator;

		return $this;
	}
		
	/**
	 * @return \Entity\User\User
	 */
	public function unsetCurrentTelmarkOperator()
	{
		$this->currentTelmarkOperator = NULL;

		return $this;
	}
		
	/**
	 * @return \Entity\User\User|NULL
	 */
	public function getCurrentTelmarkOperator()
	{
		return $this->currentTelmarkOperator;
	}
		
	/**
	 * @param \Entity\User\Combination
	 * @return \Entity\User\User
	 */
	public function addCombination(\Entity\User\Combination $combination)
	{
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
	public function removeCombination(\Entity\User\Combination $combination)
	{
		if($this->combinations->contains($combination)) {
			$this->combinations->removeElement($combination);
		}
		$combination->unsetUser();

		return $this;
	}
		
	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection of \Entity\User\Combination
	 */
	public function getCombinations()
	{
		return $this->combinations;
	}
		
	/**
	 * @param \Entity\Rental\Rental
	 * @return \Entity\User\User
	 */
	public function addRental(\Entity\Rental\Rental $rental)
	{
		if(!$this->rentals->contains($rental)) {
			$this->rentals->add($rental);
		}
		$rental->setUser($this);

		return $this;
	}
		
	/**
	 * @param \Entity\Rental\Rental
	 * @return \Entity\User\User
	 */
	public function removeRental(\Entity\Rental\Rental $rental)
	{
		if($this->rentals->contains($rental)) {
			$this->rentals->removeElement($rental);
		}
		$rental->unsetUser();

		return $this;
	}
		
	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection of \Entity\Rental\Rental
	 */
	public function getRentals()
	{
		return $this->rentals;
	}
		
	/**
	 * @param \Entity\Task\Task
	 * @return \Entity\User\User
	 */
	public function addTask(\Entity\Task\Task $task)
	{
		if(!$this->tasks->contains($task)) {
			$this->tasks->add($task);
		}

		return $this;
	}
		
	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection of \Entity\Task\Task
	 */
	public function getTasks()
	{
		return $this->tasks;
	}
		
	/**
	 * @param boolean
	 * @return \Entity\User\User
	 */
	public function setSubscribed($subscribed)
	{
		$this->subscribed = $subscribed;

		return $this;
	}
		
	/**
	 * @return \Entity\User\User
	 */
	public function unsetSubscribed()
	{
		$this->subscribed = NULL;

		return $this;
	}
		
	/**
	 * @return boolean|NULL
	 */
	public function getSubscribed()
	{
		return $this->subscribed;
	}
		
	/**
	 * @param boolean
	 * @return \Entity\User\User
	 */
	public function setBanned($banned)
	{
		$this->banned = $banned;

		return $this;
	}
		
	/**
	 * @return \Entity\User\User
	 */
	public function unsetBanned()
	{
		$this->banned = NULL;

		return $this;
	}
		
	/**
	 * @return boolean|NULL
	 */
	public function getBanned()
	{
		return $this->banned;
	}
		
	/**
	 * @param boolean
	 * @return \Entity\User\User
	 */
	public function setFull($full)
	{
		$this->full = $full;

		return $this;
	}
		
	/**
	 * @return \Entity\User\User
	 */
	public function unsetFull()
	{
		$this->full = NULL;

		return $this;
	}
		
	/**
	 * @return boolean|NULL
	 */
	public function getFull()
	{
		return $this->full;
	}
		
	/**
	 * @param boolean
	 * @return \Entity\User\User
	 */
	public function setSpam($spam)
	{
		$this->spam = $spam;

		return $this;
	}
		
	/**
	 * @return \Entity\User\User
	 */
	public function unsetSpam()
	{
		$this->spam = NULL;

		return $this;
	}
		
	/**
	 * @return boolean|NULL
	 */
	public function getSpam()
	{
		return $this->spam;
	}
		
	/**
	 * @param \Entity\Ticket\Message
	 * @return \Entity\User\User
	 */
	public function addTicketMessage(\Entity\Ticket\Message $ticketMessage)
	{
		if(!$this->ticketMessages->contains($ticketMessage)) {
			$this->ticketMessages->add($ticketMessage);
		}

		return $this;
	}
		
	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection of \Entity\Ticket\Message
	 */
	public function getTicketMessages()
	{
		return $this->ticketMessages;
	}
}