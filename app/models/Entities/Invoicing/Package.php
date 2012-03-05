<?php

namespace Entities\Invoicing;

use Entities\Dictionary;
use Entities\Invoicing;
use Entities\Location;
use Doctrine\Common\Collections\Collection
use Doctrine\Common\Collections\ArrayCollection


/**
 * @Entity()
 * @Table(name="invoicing_package")
 */
class Package extends \BaseEntity {

	/**
	 * @var Collection
	 * @ManyToMany(targetEntity="Dictionary\Phrase")
	 */
	protected $name;

	/**
	 * @var Collection
	 * @ManyToMany(targetEntity="Dictionary\Phrase")
	 */
	protected $teaser;

	/**
	 * @var Collection
	 * @Column(type="Use")
	 */
	protected $uses;

	/**
	 * @var Collection
	 * @OneToOne(targetEntity="Location\Location")
	 */
	protected $country;

	/**
	 * @var Collection
	 * @OneToMany(targetEntity="Invoicing\Service\Service", mappedBy="package")
	 */
	protected $services;


	public function __construct() {
		$this->services = new ArrayCollection();
	}


	/**
	 * @param Dictionary\Phrase $name
	 * @return Package
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
	 * @param Dictionary\Phrase $teaser
	 * @return Package
	 */
	public function setTeaser(Dictionary\Phrase  $teaser) {
		$this->teaser = $teaser;
		return $this;
	}


	/**
	 * @return Dictionary\Phrase
	 */
	public function getTeaser() {
		return $this->teaser;
	}


	/**
	 * @param Use $uses
	 * @return Package
	 */
	public function setUses(Use  $uses) {
		$this->uses = $uses;
		return $this;
	}


	/**
	 * @return Use
	 */
	public function getUses() {
		return $this->uses;
	}


	/**
	 * @param Location\Location $country
	 * @return Package
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
	 * @param Invoicing\Service\Service $service
	 * @return Package
	 */
	public function addService(Invoicing\Service\Service  $service) {
		$this->services->add($service);
		return $this;
	}


	/**
	 * @param Invoicing\Service\Service $service
	 * @return Package
	 */
	public function removeService(Invoicing\Service\Service  $service) {
		$this->services->removeElement($service);
		return $this;
	}


	/**
	 * @return Invoicing\Service\Service[]
	 */
	public function getService() {
		return $this->services->toArray();
	}

}
