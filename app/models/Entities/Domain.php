<?php

namespace Entities;

use Location;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;


/**
 * @ORM\Entity()
 * @ORM\Table(name="domain")
 */
class Domain extends \BaseEntity {

	/**
	 * @var string
	 * @ORM\ManyToMany(type="string", nullable=true)
	 */
	protected $domain;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Location\Location")
	 */
	protected $locations;


	public function __construct() {
		$this->locations = new ArrayCollection();
	}


	/**
	 * @param string $domain
	 * @return Domain
	 */
	public function setDomain($domain) {
		$this->domain = $domain;
		return $this;
	}


	/**
	 * @return string
	 */
	public function getDomain() {
		return $this->domain;
	}


	/**
	 * @param Location\Location $location
	 * @return Domain
	 */
	public function addLocation(Location\Location  $location) {
		$this->locations->add($location);
		return $this;
	}


	/**
	 * @param Location\Location $location
	 * @return Domain
	 */
	public function removeLocation(Location\Location  $location) {
		$this->locations->removeElement($location);
		return $this;
	}


	/**
	 * @return Location\Location[]
	 */
	public function getLocation() {
		return $this->locations->toArray();
	}

}
