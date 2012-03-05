<?php

namespace Entities\User;

use Entities\Dictionary;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="user_role")
 */
class Role extends \BaseEntity {

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Dictionary\Phrase")
	 */
	protected $name;

	/**
	 * @var url
	 * @ORM\ManyToMany(type="url")
	 */
	protected $homePage;


	public function __construct() {

	}


	/**
	 * @param Dictionary\Phrase $name
	 * @return Role
	 */
	public function setName(Dictionary\Phrase  $name) {
		$this->name = $name;
		return $this;
	}


	/**
	 * @return Dictionary\Phrase
	 */
	public function getName() {
		return $this->name;
	}


	/**
	 * @param url $homePage
	 * @return Role
	 */
	public function setHomePage($homePage) {
		$this->homePage = $homePage;
		return $this;
	}


	/**
	 * @return url
	 */
	public function getHomePage() {
		return $this->homePage;
	}

}
