<?php

namespace Entity\User;

use Entity\Phrase;
use Entity\Location;
use Doctrine\ORM\Mapping as ORM;
use	Extras\Annotation as EA;

/**
 * @ORM\Entity()
 * @ORM\Table(name="user_combination")
 * @EA\Primary(key="id", value="id")
 */
class Combination extends \Entity\BaseEntityDetails {

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="User", inversedBy="combinations")
	 */
	protected $user;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entity\Location\Location")
	 */
	protected $country;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entity\Language")
	 */
	protected $language;


			//@entity-generator-code --- NEMAZAT !!!

	/* ----------------------------- Methods ----------------------------- */		
	public function __construct()
	{
		parent::__construct();
	}
		
	/**
	 * @param \Entity\User\User
	 * @return \Entity\User\Combination
	 */
	public function setUser(\Entity\User\User $user)
	{
		$this->user = $user;

		return $this;
	}
		
	/**
	 * @return \Entity\User\Combination
	 */
	public function unsetUser()
	{
		$this->user = NULL;

		return $this;
	}
		
	/**
	 * @return \Entity\User\User|NULL
	 */
	public function getUser()
	{
		return $this->user;
	}
		
	/**
	 * @param \Entity\Location\Location
	 * @return \Entity\User\Combination
	 */
	public function setCountry(\Entity\Location\Location $country)
	{
		$this->country = $country;

		return $this;
	}
		
	/**
	 * @return \Entity\User\Combination
	 */
	public function unsetCountry()
	{
		$this->country = NULL;

		return $this;
	}
		
	/**
	 * @return \Entity\Location\Location|NULL
	 */
	public function getCountry()
	{
		return $this->country;
	}
		
	/**
	 * @param \Entity\Language
	 * @return \Entity\User\Combination
	 */
	public function setLanguage(\Entity\Language $language)
	{
		$this->language = $language;

		return $this;
	}
		
	/**
	 * @return \Entity\User\Combination
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
}