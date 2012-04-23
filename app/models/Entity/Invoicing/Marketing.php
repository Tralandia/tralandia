<?php

namespace Entity\Invoicing;

use Entity\Dictionary;
use Entity\Invoicing;
use Entity\Location;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="invoicing_marketing")
 */
class Marketing extends \Entity\BaseEntity {

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Entity\Dictionary\Phrase", cascade={"persist", "remove"})
	 */
	protected $name;

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Entity\Dictionary\Phrase", cascade={"persist", "remove"})
	 */
	protected $description;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Package")
	 */
	protected $package;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Entity\Location\Location", mappedBy="marketings")
	 */
	protected $locations;

	/**
	 * @var integer
	 * @ORM\Column(type="integer")
	 */
	protected $countTotal;

	/**
	 * @var integer
	 * @ORM\Column(type="integer")
	 */
	protected $countLeft;

	/**
	 * @var datetime
	 * @ORM\Column(type="datetime")
	 */
	protected $validFrom;

	/**
	 * @var datetime
	 * @ORM\Column(type="datetime")
	 */
	protected $validTo;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="UseType", mappedBy="marketings")
	 */
	protected $uses;

	














//@entity-generator-code <--- NEMAZAT !!!

	/* ----------------------------- Methods ----------------------------- */		
	public function __construct() {
		parent::__construct();

		$this->locations = new \Doctrine\Common\Collections\ArrayCollection;
		$this->uses = new \Doctrine\Common\Collections\ArrayCollection;
	}
		
	/**
	 * @param \Entity\Dictionary\Phrase
	 * @return \Entity\Invoicing\Marketing
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
	 * @return \Entity\Invoicing\Marketing
	 */
	public function setDescription(\Entity\Dictionary\Phrase $description) {
		$this->description = $description;

		return $this;
	}
		
	/**
	 * @return \Entity\Dictionary\Phrase|NULL
	 */
	public function getDescription() {
		return $this->description;
	}
		
	/**
	 * @param \Entity\Invoicing\Package
	 * @return \Entity\Invoicing\Marketing
	 */
	public function setPackage(\Entity\Invoicing\Package $package) {
		$this->package = $package;

		return $this;
	}
		
	/**
	 * @return \Entity\Invoicing\Marketing
	 */
	public function unsetPackage() {
		$this->package = NULL;

		return $this;
	}
		
	/**
	 * @return \Entity\Invoicing\Package|NULL
	 */
	public function getPackage() {
		return $this->package;
	}
		
	/**
	 * @param \Entity\Location\Location
	 * @return \Entity\Invoicing\Marketing
	 */
	public function addLocation(\Entity\Location\Location $location) {
		if(!$this->locations->contains($location)) {
			$this->locations->add($location);
		}
		$location->addMarketing($this);

		return $this;
	}
		
	/**
	 * @param \Entity\Location\Location
	 * @return \Entity\Invoicing\Marketing
	 */
	public function removeLocation(\Entity\Location\Location $location) {
		if($this->locations->contains($location)) {
			$this->locations->removeElement($location);
		}
		$location->removeMarketing($this);

		return $this;
	}
		
	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection of \Entity\Location\Location
	 */
	public function getLocations() {
		return $this->locations;
	}
		
	/**
	 * @param integer
	 * @return \Entity\Invoicing\Marketing
	 */
	public function setCountTotal($countTotal) {
		$this->countTotal = $countTotal;

		return $this;
	}
		
	/**
	 * @return integer|NULL
	 */
	public function getCountTotal() {
		return $this->countTotal;
	}
		
	/**
	 * @param integer
	 * @return \Entity\Invoicing\Marketing
	 */
	public function setCountLeft($countLeft) {
		$this->countLeft = $countLeft;

		return $this;
	}
		
	/**
	 * @return integer|NULL
	 */
	public function getCountLeft() {
		return $this->countLeft;
	}
		
	/**
	 * @param \DateTime
	 * @return \Entity\Invoicing\Marketing
	 */
	public function setValidFrom(\DateTime $validFrom) {
		$this->validFrom = $validFrom;

		return $this;
	}
		
	/**
	 * @return \DateTime|NULL
	 */
	public function getValidFrom() {
		return $this->validFrom;
	}
		
	/**
	 * @param \DateTime
	 * @return \Entity\Invoicing\Marketing
	 */
	public function setValidTo(\DateTime $validTo) {
		$this->validTo = $validTo;

		return $this;
	}
		
	/**
	 * @return \DateTime|NULL
	 */
	public function getValidTo() {
		return $this->validTo;
	}
		
	/**
	 * @param \Entity\Invoicing\UseType
	 * @return \Entity\Invoicing\Marketing
	 */
	public function addUse(\Entity\Invoicing\UseType $use) {
		if(!$this->uses->contains($use)) {
			$this->uses->add($use);
		}
		$use->addMarketing($this);

		return $this;
	}
		
	/**
	 * @param \Entity\Invoicing\UseType
	 * @return \Entity\Invoicing\Marketing
	 */
	public function removeUse(\Entity\Invoicing\UseType $use) {
		if($this->uses->contains($use)) {
			$this->uses->removeElement($use);
		}
		$use->removeMarketing($this);

		return $this;
	}
		
	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection of \Entity\Invoicing\UseType
	 */
	public function getUses() {
		return $this->uses;
	}
}