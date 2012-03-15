<?php

namespace Entities;

use Entities\Location;
use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity()
 * @ORM\Table(name="domain")
 */
class Domain extends \Entities\BaseEntity {

	/**
	 * @var string
	 * @ORM\Column(type="string")
	 */
	protected $domain;

	/**
	 * @var Collection
	 * @ORM\OneToMany(targetEntity="Entities\Location\Location", mappedBy="domain")
	 */
	protected $locations;

	public function __construct() {
	    parent::__construct();
	}
	 
	/**
	 * @param string
	 * @return \Entities\Domain
	 */
	public function setDomain($domain) {
	    $this->domain = $domain;
	 
	    return $this;
	}
	 
	/**
	 * @return \Entities\Domain
	 */
	public function unsetDomain() {
	    $this->domain = NULL;
	 
	    return $this;
	}
	 
	/**
	 * @return string|NULL
	 */
	public function getDomain() {
	    return $this->domain;
	}
	 
	/**
	 * @param \Entities\Location\Location
	 * @return \Entities\Domain
	 */
	public function addLocation(\Entities\Location\Location $location) {
	    if(!$this->locations->contains($location)) {
	        $this->locations->add($location);
	    }
	 
	    return $this;
	}
	 
	/**
	 * @param \Entities\Location\Location
	 * @return \Entities\Domain
	 */
	public function removeLocation(\Entities\Location\Location $location) {
	    if($this->locations->contains($location)) {
	        $this->locations->removeElement($location);
	    }
	 
	    return $this;
	}
	 
	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection of \Entities\Location\Location
	 */
	public function getLocations() {
	    return $this->locations;
	}

}