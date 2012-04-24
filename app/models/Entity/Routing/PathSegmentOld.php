<?php

namespace Entity\Routing;

use Entity\Dictionary;
use Entity\Location;
use Entity\Routing;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="routing_pathsegmentold")
 */
class PathSegmentOld extends \Entity\BaseEntity {

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="PathSegment")
	 */
	protected $pathSegmentNew;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entity\Location\Location")
	 */
	protected $country;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entity\Dictionary\Language")
	 */
	protected $language;

	/**
	 * @var string
	 * @ORM\Column(type="string")
	 */
	protected $pathSegment;

	/**
	 * @var Integer
	 * @ORM\Column(type="integer")
	 */
	protected $type;

	/**
	 * @var integer
	 * @ORM\Column(type="integer")
	 */
	protected $entityId;

	














//@entity-generator-code <--- NEMAZAT !!!

	/* ----------------------------- Methods ----------------------------- */		
	public function __construct() {
		parent::__construct();
	}
		
	/**
	 * @param \Entity\Routing\PathSegment
	 * @return \Entity\Routing\PathSegmentOld
	 */
	public function setPathSegmentNew(\Entity\Routing\PathSegment $pathSegmentNew) {
		$this->pathSegmentNew = $pathSegmentNew;

		return $this;
	}
		
	/**
	 * @return \Entity\Routing\PathSegmentOld
	 */
	public function unsetPathSegmentNew() {
		$this->pathSegmentNew = NULL;

		return $this;
	}
		
	/**
	 * @return \Entity\Routing\PathSegment|NULL
	 */
	public function getPathSegmentNew() {
		return $this->pathSegmentNew;
	}
		
	/**
	 * @param \Entity\Location\Location
	 * @return \Entity\Routing\PathSegmentOld
	 */
	public function setCountry(\Entity\Location\Location $country) {
		$this->country = $country;

		return $this;
	}
		
	/**
	 * @return \Entity\Routing\PathSegmentOld
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
	 * @param \Entity\Dictionary\Language
	 * @return \Entity\Routing\PathSegmentOld
	 */
	public function setLanguage(\Entity\Dictionary\Language $language) {
		$this->language = $language;

		return $this;
	}
		
	/**
	 * @return \Entity\Routing\PathSegmentOld
	 */
	public function unsetLanguage() {
		$this->language = NULL;

		return $this;
	}
		
	/**
	 * @return \Entity\Dictionary\Language|NULL
	 */
	public function getLanguage() {
		return $this->language;
	}
		
	/**
	 * @param string
	 * @return \Entity\Routing\PathSegmentOld
	 */
	public function setPathSegment($pathSegment) {
		$this->pathSegment = $pathSegment;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getPathSegment() {
		return $this->pathSegment;
	}
		
	/**
	 * @param integer
	 * @return \Entity\Routing\PathSegmentOld
	 */
	public function setType($type) {
		$this->type = $type;

		return $this;
	}
		
	/**
	 * @return integer|NULL
	 */
	public function getType() {
		return $this->type;
	}
		
	/**
	 * @param integer
	 * @return \Entity\Routing\PathSegmentOld
	 */
	public function setEntityId($entityId) {
		$this->entityId = $entityId;

		return $this;
	}
		
	/**
	 * @return integer|NULL
	 */
	public function getEntityId() {
		return $this->entityId;
	}
}