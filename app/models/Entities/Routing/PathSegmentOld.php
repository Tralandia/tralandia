<?php

namespace Routing;

use Dictionary;
use Location;
use Routing;
use Doctrine\Common\Collections\Collection
use Doctrine\Common\Collections\ArrayCollection


/**
 * @Entity()
 * @Table(name="routing_pathsegmentold")
 */
class PathSegmentOld extends \BaseEntity {

	/**
	 * @var string
	 * @Column(type="string", nullable=true)
	 */
	protected $pathSegment;

	/**
	 * @var Collection
	 * @ManyToMany(targetEntity="Location\Location")
	 */
	protected $country;

	/**
	 * @var Collection
	 * @ManyToMany(targetEntity="Dictionary\Language")
	 */
	protected $language;

	/**
	 * @var Integer
	 * @Column(type="Integer")
	 */
	protected $type;

	/**
	 * @var integer
	 * @Column(type="integer")
	 */
	protected $entityId;


	public function __construct() {

	}


	/**
	 * @param string $pathSegment
	 * @return PathSegmentOld
	 */
	public function setPathSegment($pathSegment) {
		$this->pathSegment = $pathSegment;
		return $this;
	}


	/**
	 * @return string
	 */
	public function getPathSegment() {
		return $this->pathSegment;
	}


	/**
	 * @param Location\Location $country
	 * @return PathSegmentOld
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
	 * @param Dictionary\Language $language
	 * @return PathSegmentOld
	 */
	public function setLanguage(Dictionary\Language  $language) {
		$this->language = $language;
		return $this;
	}


	/**
	 * @return Dictionary\Language
	 */
	public function getLanguage() {
		return $this->language;
	}


	/**
	 * @param Integer $type
	 * @return PathSegmentOld
	 */
	public function setType($type) {
		$this->type = $type;
		return $this;
	}


	/**
	 * @return Integer
	 */
	public function getType() {
		return $this->type;
	}


	/**
	 * @param integer $entityId
	 * @return PathSegmentOld
	 */
	public function setEntityId($entityId) {
		$this->entityId = $entityId;
		return $this;
	}


	/**
	 * @return integer
	 */
	public function getEntityId() {
		return $this->entityId;
	}

}
