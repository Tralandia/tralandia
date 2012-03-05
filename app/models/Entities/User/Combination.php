<?php

namespace Entities\User;

use Entities\Dictionary;
use Entities\Location;
use Entities\User;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="user_combination")
 */
class Combination extends \BaseEntityDetails {

	/**
	 * @var Collection
	 * @ORM\ManyToMany(type="User")
	 */
	protected $user;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Location\Location")
	 */
	protected $country;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Dictionary\Language")
	 */
	protected $language;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(type="integer")
	 */
	protected $languageLevel;


	public function __construct() {
		parent::__construct();

	}


	/**
	 * @param User $user
	 * @return Combination
	 */
	public function setUser(User  $user) {
		$this->user = $user;
		return $this;
	}


	/**
	 * @return User
	 */
	public function getUser() {
		return $this->user;
	}


	/**
	 * @param Location\Location $country
	 * @return Combination
	 */
	public function setCountry(Location\Location  $country) {
		$this->country = $country;
		return $this;
	}


	/**
	 * @return Location\Location
	 */
	public function getCountry() {
		return $this->country;
	}


	/**
	 * @param Dictionary\Language $language
	 * @return Combination
	 */
	public function setLanguage(Dictionary\Language  $language) {
		$this->language = $language;
		return $this;
	}


	/**
	 * @return Dictionary\Language
	 */
	public function getLanguage() {
		return $this->language;
	}

}
