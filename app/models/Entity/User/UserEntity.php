<?php

namespace Entity\User;

use Entity\Phrase;
use Entity\Location;
use Entity\Rental;
use Doctrine\ORM\Mapping as ORM;
use	Extras\Annotation as EA;


/**
 * @ORM\Entity(repositoryClass="Repository\User\UserRepository")
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
	protected $newsletter;


	public function getIdentity()
	{
		$identity = [
			'id' => $this->getId(),
			'login' => $this->getLogin(),
			'homepage' => $this->getRole()->getHomePage(),
		];

		return $identity;
	}

	//@entity-generator-code --- NEMAZAT !!!

	/* ----------------------------- Methods ----------------------------- */		
	public function __construct()
	{
		parent::__construct();

		$this->rentals = new \Doctrine\Common\Collections\ArrayCollection;
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
	 * @return \Doctrine\Common\Collections\ArrayCollection|\Entity\Rental\Rental[]
	 */
	public function getRentals()
	{
		return $this->rentals;
	}
		
	/**
	 * @param boolean
	 * @return \Entity\User\User
	 */
	public function setNewsletter($newsletter)
	{
		$this->newsletter = $newsletter;

		return $this;
	}
		
	/**
	 * @return \Entity\User\User
	 */
	public function unsetNewsletter()
	{
		$this->newsletter = NULL;

		return $this;
	}
		
	/**
	 * @return boolean|NULL
	 */
	public function getNewsletter()
	{
		return $this->newsletter;
	}

}