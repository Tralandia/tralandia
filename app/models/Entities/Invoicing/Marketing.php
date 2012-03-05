<?php

namespace Entities\Invoicing;

use Entities\Dictionary;
use Entities\Invoicing;
use Entities\Location;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;


/**
 * @ORM\Entity()
 * @ORM\Table(name="invoicing_marketing")
 */
class Marketing extends \BaseEntity {

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Dictionary\Phrase")
	 */
	protected $name;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Dictionary\Phrase")
	 */
	protected $description;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(type="Package")
	 */
	protected $package;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Location\Location")
	 */
	protected $locationsIncluded;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Location\Location")
	 */
	protected $locationsExcluded;

	/**
	 * @var integer
	 * @ORM\ManyToMany(type="integer")
	 */
	protected $countTotal;

	/**
	 * @var integer
	 * @ORM\ManyToMany(type="integer")
	 */
	protected $countLeft;

	/**
	 * @var datetime
	 * @ORM\ManyToMany(type="datetime")
	 */
	protected $validFrom;

	/**
	 * @var datetime
	 * @ORM\ManyToMany(type="datetime")
	 */
	protected $validTo;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(type="Use")
	 */
	protected $uses;


	public function __construct() {
		$this->locationsIncluded = new ArrayCollection();
		$this->locationsExcluded = new ArrayCollection();
	}


	/**
	 * @param Dictionary\Phrase $name
	 * @return Marketing
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
	 * @param Dictionary\Phrase $description
	 * @return Marketing
	 */
	public function setDescription(Dictionary\Phrase  $description) {
		$this->description = $description;
		return $this;
	}


	/**
	 * @return Dictionary\Phrase
	 */
	public function getDescription() {
		return $this->description;
	}


	/**
	 * @param Package $package
	 * @return Marketing
	 */
	public function setPackage(Package  $package) {
		$this->package = $package;
		return $this;
	}


	/**
	 * @return Package
	 */
	public function getPackage() {
		return $this->package;
	}


	/**
	 * @param Location\Location $locationsIncluded
	 * @return Marketing
	 */
	public function addLocationsIncluded(Location\Location  $locationsIncluded) {
		$this->locationsIncluded->add($locationsIncluded);
		return $this;
	}


	/**
	 * @param Location\Location $locationsIncluded
	 * @return Marketing
	 */
	public function removeLocationsIncluded(Location\Location  $locationsIncluded) {
		$this->locationsIncluded->removeElement($locationsIncluded);
		return $this;
	}


	/**
	 * @return Location\Location[]
	 */
	public function getLocationsIncluded() {
		return $this->locationsIncluded->toArray();
	}


	/**
	 * @param Location\Location $locationsExcluded
	 * @return Marketing
	 */
	public function addLocationsExcluded(Location\Location  $locationsExcluded) {
		$this->locationsExcluded->add($locationsExcluded);
		return $this;
	}


	/**
	 * @param Location\Location $locationsExcluded
	 * @return Marketing
	 */
	public function removeLocationsExcluded(Location\Location  $locationsExcluded) {
		$this->locationsExcluded->removeElement($locationsExcluded);
		return $this;
	}


	/**
	 * @return Location\Location[]
	 */
	public function getLocationsExcluded() {
		return $this->locationsExcluded->toArray();
	}


	/**
	 * @param integer $countTotal
	 * @return Marketing
	 */
	public function setCountTotal($countTotal) {
		$this->countTotal = $countTotal;
		return $this;
	}


	/**
	 * @return integer
	 */
	public function getCountTotal() {
		return $this->countTotal;
	}


	/**
	 * @param integer $countLeft
	 * @return Marketing
	 */
	public function setCountLeft($countLeft) {
		$this->countLeft = $countLeft;
		return $this;
	}


	/**
	 * @return integer
	 */
	public function getCountLeft() {
		return $this->countLeft;
	}


	/**
	 * @param datetime $validFrom
	 * @return Marketing
	 */
	public function setValidFrom($validFrom) {
		$this->validFrom = $validFrom;
		return $this;
	}


	/**
	 * @return datetime
	 */
	public function getValidFrom() {
		return $this->validFrom;
	}


	/**
	 * @param datetime $validTo
	 * @return Marketing
	 */
	public function setValidTo($validTo) {
		$this->validTo = $validTo;
		return $this;
	}


	/**
	 * @return datetime
	 */
	public function getValidTo() {
		return $this->validTo;
	}


	/**
	 * @param Use $uses
	 * @return Marketing
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

}
