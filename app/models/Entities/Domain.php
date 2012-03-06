<?php

namespace Entities;

use Entities\Location;
use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity()
 * @ORM\Table(name="domain")
 */
class Domain extends \BaseEntity {

	/**
	 * @var string
	 * @ORM\Column(type="string")
	 */
	protected $domain;

	/**
	 * @var Collection
	 * @ORM\OneToMany(targetEntity="Location\Location")
	 */
	protected $locations;


	public function __construct() {
		parent::__construct();
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
		if(!$this->locations->contains($location)) {
			$this->locations->add($location);
		}

		return $this;
	}


	/**
	 * @param Location\Location $location
	 * @return Domain
	 */
	public function removeLocation(Location\Location  $location) {
		if($this->locations->contains($location)) {
			$this->locations->removeElement($location);
		}

		return $this;
	}


	/**
	 * @return Location\Location[]
	 */
	public function getLocation() {
		return $this->locations->toArray();
	}

}
