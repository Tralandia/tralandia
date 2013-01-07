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
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entity\Language")
	 */
	protected $language;

	/**
	 * @var Collection
	 * @ORM\OneToMany(targetEntity="Entity\Rental\Rental", mappedBy="user", cascade={"persist"})
	 * @EA\SingularName(name="rental") 
	 */
	protected $rentals;

	/**
	 * @var boolean
	 * @ORM\Column(type="boolean", nullable=true)
	 */
	protected $newsletterMarketing;

	/**
	 * @var boolean
	 * @ORM\Column(type="boolean", nullable=true)
	 */
	protected $newsletterNews;


	//@entity-generator-code --- NEMAZAT !!!

	/* ----------------------------- Methods ----------------------------- */		
	public function __construct()
	{
		parent::__construct();

		$this->phones = new \Doctrine\Common\Collections\ArrayCollection;
		$this->emails = new \Doctrine\Common\Collections\ArrayCollection;
		$this->urls = new \Doctrine\Common\Collections\ArrayCollection;
		$this->combinations = new \Doctrine\Common\Collections\ArrayCollection;
		$this->rentals = new \Doctrine\Common\Collections\ArrayCollection;
		$this->tasks = new \Doctrine\Common\Collections\ArrayCollection;
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
	public function setName($name)
	{
		$this->name = $name;

		return $this;
	}
		
	/**
	 * @return \Entity\User\User
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
	 * @param \Entity\Contact\Phone
	 * @return \Entity\User\User
	 */
	public function addPhone(\Entity\Contact\Phone $phone)
	{
		if(!$this->phones->contains($phone)) {
			$this->phones->add($phone);
		}
		$phone->addUser($this);

		return $this;
	}
		
	/**
	 * @param \Entity\Contact\Phone
	 * @return \Entity\User\User
	 */
	public function removePhone(\Entity\Contact\Phone $phone)
	{
		$this->phones->removeElement($phone);
		$phone->removeUser($this);

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
		$email->addUser($this);

		return $this;
	}
		
	/**
	 * @param \Entity\Contact\Email
	 * @return \Entity\User\User
	 */
	public function removeEmail(\Entity\Contact\Email $email)
	{
		$this->emails->removeElement($email);
		$email->removeUser($this);

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
		$url->addUser($this);

		return $this;
	}
		
	/**
	 * @param \Entity\Contact\Url
	 * @return \Entity\User\User
	 */
	public function removeUrl(\Entity\Contact\Url $url)
	{
		$this->urls->removeElement($url);
		$url->removeUser($this);

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
		$this->combinations->removeElement($combination);
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
		$this->rentals->removeElement($rental);
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
	 * @param \Entity\Task\Task
	 * @return \Entity\User\User
	 */
	public function removeTask(\Entity\Task\Task $task)
	{
		$this->tasks->removeElement($task);

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
	public function setNewsletterMarketing($newsletterMarketing)
	{
		$this->newsletterMarketing = $newsletterMarketing;

		return $this;
	}
		
	/**
	 * @return \Entity\User\User
	 */
	public function unsetNewsletterMarketing()
	{
		$this->newsletterMarketing = NULL;

		return $this;
	}
		
	/**
	 * @return boolean|NULL
	 */
	public function getNewsletterMarketing()
	{
		return $this->newsletterMarketing;
	}
		
	/**
	 * @param boolean
	 * @return \Entity\User\User
	 */
	public function setNewsletterNews($newsletterNews)
	{
		$this->newsletterNews = $newsletterNews;

		return $this;
	}
		
	/**
	 * @return \Entity\User\User
	 */
	public function unsetNewsletterNews()
	{
		$this->newsletterNews = NULL;

		return $this;
	}
		
	/**
	 * @return boolean|NULL
	 */
	public function getNewsletterNews()
	{
		return $this->newsletterNews;
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
}