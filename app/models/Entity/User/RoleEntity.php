<?php

namespace Entity\User;

use Entity\Phrase;
use Doctrine\ORM\Mapping as ORM;
use	Extras\Annotation as EA;

/**
 * @ORM\Entity(repositoryClass="Repository\User\RoleRepository")
 * @ORM\Table(name="user_role", indexes={@ORM\index(name="slug", columns={"slug"})})
 * @EA\Primary(key="id", value="name")
 * @EA\Generator(skip="{setSlug}")
 */
class Role extends \Entity\BaseEntity {

	const SUPERADMIN = 'superadmin';
	const TRANSLATOR = 'translator';
	const OWNER = 'owner';
	const GUEST = 'guest';

	/**
	 * @var string
	 * @ORM\Column(type="string")
	 */
	protected $name;

	/**
	 * @var string
	 * @ORM\Column(type="string")
	 */
	protected $slug;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $homePage;

	/**
	 * @var Collection
	 * @ORM\OneToMany(targetEntity="Entity\User\User", mappedBy="role", cascade={"persist"})
	 */
	protected $users;

		
	/**
	 * @param string
	 * @return \Entity\User\Role
	 */
	public function setSlug($slug)
	{
		$this->slug = \Nette\Utils\Strings::webalize($slug);

		return $this;
	}

	//@entity-generator-code --- NEMAZAT !!!

	/* ----------------------------- Methods ----------------------------- */		
	public function __construct()
	{
		parent::__construct();

		$this->users = new \Doctrine\Common\Collections\ArrayCollection;
	}
		
	/**
	 * @param string
	 * @return \Entity\User\Role
	 */
	public function setName($name)
	{
		$this->name = $name;

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
	 * @return string|NULL
	 */
	public function getSlug()
	{
		return $this->slug;
	}
		
	/**
	 * @param string
	 * @return \Entity\User\Role
	 */
	public function setHomePage($homePage)
	{
		$this->homePage = $homePage;

		return $this;
	}
		
	/**
	 * @return \Entity\User\Role
	 */
	public function unsetHomePage()
	{
		$this->homePage = NULL;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getHomePage()
	{
		return $this->homePage;
	}
		
	/**
	 * @param \Entity\User\User
	 * @return \Entity\User\Role
	 */
	public function addUser(\Entity\User\User $user)
	{
		if(!$this->users->contains($user)) {
			$this->users->add($user);
		}
		$user->setRole($this);

		return $this;
	}
		
	/**
	 * @param \Entity\User\User
	 * @return \Entity\User\Role
	 */
	public function removeUser(\Entity\User\User $user)
	{
		$this->users->removeElement($user);
		$user->unsetRole();

		return $this;
	}
		
	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection|\Entity\User\User[]
	 */
	public function getUsers()
	{
		return $this->users;
	}
}