<?php

namespace Entity\Invoicing;

use Entity\Dictionary;
use Entity\Location;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="invoicing_package")
 */
class Package extends \Entity\BaseEntity {

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Entity\Dictionary\Phrase", cascade={"persist", "remove"})
	 */
	protected $name;

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Entity\Dictionary\Phrase", cascade={"persist", "remove"})
	 */
	protected $teaser;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="UseType", mappedBy="packages")
	 */
	protected $uses;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entity\Location\Location")
	 */
	protected $country;

	/**
	 * @var Collection
	 * @ORM\OneToMany(targetEntity="Entity\Invoicing\Service\Service", mappedBy="package")
	 */
	protected $services;

	






//@entity-generator-code <--- NEMAZAT !!!

	/* ----------------------------- Methods ----------------------------- */		
	public function __construct() {
		parent::__construct();

		$this->uses = new \Doctrine\Common\Collections\ArrayCollection;
		$this->services = new \Doctrine\Common\Collections\ArrayCollection;
	}
		
	/**
	 * @param \Entity\Dictionary\Phrase
	 * @return \Entity\Invoicing\Package
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
	 * @param \Entity\Dictionary\Phrase
	 * @return \Entity\Invoicing\Package
	 */
	public function setTeaser(\Entity\Dictionary\Phrase $teaser) {
		$this->teaser = $teaser;

		return $this;
	}
		
	/**
	 * @return \Entity\Dictionary\Phrase|NULL
	 */
	public function getTeaser() {
		return $this->teaser;
	}
		
	/**
	 * @param \Entity\Invoicing\UseType
	 * @return \Entity\Invoicing\Package
	 */
	public function addUse(\Entity\Invoicing\UseType $use) {
		if(!$this->uses->contains($use)) {
			$this->uses->add($use);
		}
		$use->addPackage($this);

		return $this;
	}
		
	/**
	 * @param \Entity\Invoicing\UseType
	 * @return \Entity\Invoicing\Package
	 */
	public function removeUse(\Entity\Invoicing\UseType $use) {
		if($this->uses->contains($use)) {
			$this->uses->removeElement($use);
		}
		$use->removePackage($this);

		return $this;
	}
		
	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection of \Entity\Invoicing\UseType
	 */
	public function getUses() {
		return $this->uses;
	}
		
	/**
	 * @param \Entity\Location\Location
	 * @return \Entity\Invoicing\Package
	 */
	public function setCountry(\Entity\Location\Location $country) {
		$this->country = $country;

		return $this;
	}
		
	/**
	 * @return \Entity\Invoicing\Package
	 */
	public function unsetCountry() {
		$this->country = NULL;

		return $this;
	}
		
	/**
	 * @return \Entity\Location\Location|NULL
	 */
	public function getCountry() {
		return $this->country;
	}
		
	/**
	 * @param \Entity\Invoicing\Service\Service
	 * @return \Entity\Invoicing\Package
	 */
	public function addService(\Entity\Invoicing\Service\Service $service) {
		if(!$this->services->contains($service)) {
			$this->services->add($service);
		}
		$service->setPackage($this);

		return $this;
	}
		
	/**
	 * @param \Entity\Invoicing\Service\Service
	 * @return \Entity\Invoicing\Package
	 */
	public function removeService(\Entity\Invoicing\Service\Service $service) {
		if($this->services->contains($service)) {
			$this->services->removeElement($service);
		}
		$service->unsetPackage();

		return $this;
	}
		
	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection of \Entity\Invoicing\Service\Service
	 */
	public function getServices() {
		return $this->services;
	}
}