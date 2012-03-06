<?php

namespace Entities\Routing;

use Entities\Dictionary;
use Entities\Location;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="routing_pathsegment")
 */
class PathSegment extends \BaseEntity {

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Location\Location")
	 */
	protected $country;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Dictionary\Language")
	 */
	protected $language;

	/**
	 * @var string
	 * @ORM\ManyToMany(type="string", nullable=true)
	 */
	protected $pathSegment;

	/**
	 * @var integer
	 * @ORM\ManyToMany(type="integer")
	 */
	protected $type;

	/**
	 * @var integer
	 * @ORM\ManyToMany(type="integer")
	 */
	protected $entityId;


	public function __construct() {
		parent::__construct();

	}


	/**
	 * @param Location\Location $country
	 * @return PathSegment
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
	 * @return PathSegment
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
	 * @param string $pathSegment
	 * @return PathSegment
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
	 * @param integer $type
	 * @return PathSegment
	 */
	public function setType($type) {
		$this->type = $type;
		return $this;
	}


	/**
	 * @return integer
	 */
	public function getType() {
		return $this->type;
	}


	/**
	 * @param integer $entityId
	 * @return PathSegment
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
