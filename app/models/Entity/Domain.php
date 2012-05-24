<?php

namespace Entity;

use Entity\Location;
use Doctrine\ORM\Mapping as ORM;
use	Extras\Annotation as EA;

/**
 * @ORM\Entity()
 * @ORM\Table(name="domain", indexes={@ORM\index(name="domain", columns={"domain"})})
 * @EA\Service(name="\Service\Domain")
 * @EA\ServiceList(name="\Service\DomainList")
 * @EA\Primary(key="id", value="domain")
 */
class Domain extends \Entity\BaseEntity {

	/**
	 * @var string
	 * @ORM\Column(type="string")
	 */
	protected $domain;

	/**
	 * @var Collection
	 * @ORM\OneToMany(targetEntity="Entity\Location\Location", mappedBy="domain")
	 */
	protected $locations;


//@entity-generator-code <--- NEMAZAT !!!

	/* ----------------------------- Methods ----------------------------- */		
	public function __construct() {
		parent::__construct();

		$this->locations = new \Doctrine\Common\Collections\ArrayCollection;
	}
		
	/**
	 * @param string
	 * @return \Entity\Domain
	 */
	public function setDomain($domain) {
		$this->domain = $domain;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getDomain() {
		return $this->domain;
	}
		
	/**
	 * @param \Entity\Location\Location
	 * @return \Entity\Domain
	 */
	public function addLocation(\Entity\Location\Location $location) {
		if(!$this->locations->contains($location)) {
			$this->locations->add($location);
		}
		$location->setDomain($this);

		return $this;
	}
		
	/**
	 * @param \Entity\Location\Location
	 * @return \Entity\Domain
	 */
	public function removeLocation(\Entity\Location\Location $location) {
		if($this->locations->contains($location)) {
			$this->locations->removeElement($location);
		}
		$location->unsetDomain();

		return $this;
	}
		
	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection of \Entity\Location\Location
	 */
	public function getLocations() {
		return $this->locations;
	}
}