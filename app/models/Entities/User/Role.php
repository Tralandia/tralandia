<?php

namespace Entities\User;

use Entities\Dictionary;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="user_role")
 */
class Role extends \Entities\BaseEntity {

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $name;

	/**
	 * @var slug
	 * @ORM\Column(type="slug")
	 */
	protected $slug;

	/**
	 * @var json
	 * @ORM\Column(type="json", nullable=true)
	 */
	protected $homePage;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Entities\User\User", inversedBy="roles")
	 */
	protected $users;


	/* ----------------------------- Methods ----------------------------- */


	public function __construct() {
		parent::__construct();

		$this->users = new \Doctrine\Common\Collections\ArrayCollection;
	}

	/**
	 * @param string
	 * @return \Entities\User\Role
	 */
	public function setName($name) {
		$this->name = $name;

		return $this;
	}

	/**
	 * @return \Entities\User\Role
	 */
	public function unsetName() {
		$this->name = NULL;

		return $this;
	}

	/**
	 * @return string|NULL
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @param slug
	 * @return \Entities\User\Role
	 */
	public function setSlug($slug) {
		$this->slug = $slug;

		return $this;
	}

	/**
	 * @return slug|NULL
	 */
	public function getSlug() {
		return $this->slug;
	}

	/**
	 * @param \Extras\Types\Url
	 * @return \Entities\User\Role
	 */
	public function setHomePage(\Extras\Types\Url $homePage) {
		$this->homePage = $homePage;

		return $this;
	}

	/**
	 * @return \Extras\Types\Url|NULL
	 */
	public function getHomePage() {
		return $this->homePage;
	}

	/**
	 * @param \Entities\User\User
	 * @return \Entities\User\Role
	 */
	public function addUser(\Entities\User\User $user) {
		if(!$this->users->contains($user)) {
			$this->users->add($user);
		}

		return $this;
	}

	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection of \Entities\User\User
	 */
	public function getUsers() {
		return $this->users;
	}


}