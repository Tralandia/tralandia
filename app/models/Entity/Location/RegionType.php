<?php

namespace Entity\Location;

use Entity\Dictionary;
use Doctrine\ORM\Mapping as ORM;
use	Extras\Annotation as EA;

/**
 * @ORM\Entity()
 * @ORM\Table(name="location_regiontype")
 * @EA\Service(name="\Service\Location\RegionType")
 * @EA\ServiceList(name="\Service\Location\RegionTypeList")
 * @EA\Primary(key="id", value="name")
 */
class RegionType extends \Entity\BaseEntityDetails {

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Entity\Dictionary\Phrase", cascade={"persist", "remove"})
	 */
	protected $name;

	/**
	 * @var Collection
	 * @ORM\OneToMany(targetEntity="Entity\Location\Location", mappedBy="regionType", cascade={"persist"})
	 */
	protected $locations;

//@entity-generator-code <--- NEMAZAT !!!

	/* ----------------------------- Methods ----------------------------- */		
	public function __construct() {
		parent::__construct();

		$this->locations = new \Doctrine\Common\Collections\ArrayCollection;
	}
		
	/**
	 * @param \Entity\Dictionary\Phrase
	 * @return \Entity\Location\RegionType
	 */
	public function setName(\Entity\Dictionary\Phrase $name) {
		$this->name = $name;

		return $this;
	}
		
	/**
	 * @return \Entity\Dictionary\Phrase|NULL
	 */
	public function getName() {
		return $this->name;
	}
		
	/**
	 * @param \Entity\Location\Location
	 * @return \Entity\Location\RegionType
	 */
	public function addLocation(\Entity\Location\Location $location) {
		if(!$this->locations->contains($location)) {
			$this->locations->add($location);
		}
		$location->setRegionType($this);

		return $this;
	}
		
	/**
	 * @param \Entity\Location\Location
	 * @return \Entity\Location\RegionType
	 */
	public function removeLocation(\Entity\Location\Location $location) {
		if($this->locations->contains($location)) {
			$this->locations->removeElement($location);
		}
		$location->unsetRegionType();

		return $this;
	}
		
	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection of \Entity\Location\Location
	 */
	public function getLocations() {
		return $this->locations;
	}
}