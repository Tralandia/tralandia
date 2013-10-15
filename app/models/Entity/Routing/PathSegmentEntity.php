<?php

namespace Entity\Routing;

use Entity\Phrase;
use Entity\Location;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="routing_pathsegment", indexes={@ORM\index(name="pathSegment", columns={"pathSegment"}), @ORM\index(name="type", columns={"type"}), @ORM\index(name="entityId", columns={"entityId"})})
 */
class PathSegment extends \Entity\BaseEntity {

	const PAGE = 2;
	const ATTRACTION_TYPE = 4;
	const LOCATION = 6;
	const RENTAL_TYPE = 8;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entity\Location\Location")
	 */
	protected $primaryLocation;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entity\Language")
	 */
	protected $language;

	/**
	 * @var string
	 * @ORM\Column(type="string")
	 */
	protected $pathSegment;

	/**
	 * @var integer
	 * @ORM\Column(type="integer")
	 */
	protected $type;

	/**
	 * @var integer
	 * @ORM\Column(type="integer")
	 */
	protected $entityId;

	//@entity-generator-code --- NEMAZAT !!!

	/* ----------------------------- Methods ----------------------------- */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * @param \Entity\Location\Location
	 * @return \Entity\Routing\PathSegment
	 */
	public function setPrimaryLocation(\Entity\Location\Location $primaryLocation)
	{
		$this->primaryLocation = $primaryLocation;

		return $this;
	}

	/**
	 * @return \Entity\Routing\PathSegment
	 */
	public function unsetPrimaryLocation()
	{
		$this->primaryLocation = NULL;

		return $this;
	}

	/**
	 * @return \Entity\Location\Location|NULL
	 */
	public function getPrimaryLocation()
	{
		return $this->primaryLocation;
	}

	/**
	 * @param \Entity\Language
	 * @return \Entity\Routing\PathSegment
	 */
	public function setLanguage(\Entity\Language $language)
	{
		$this->language = $language;

		return $this;
	}

	/**
	 * @return \Entity\Routing\PathSegment
	 */
	public function unsetLanguage()
	{
		$this->language = NULL;

		return $this;
	}

	/**
	 * @return \Entity\Language|NULL
	 */
	public function getLanguage()
	{
		return $this->language;
	}

	/**
	 * @param string
	 * @return \Entity\Routing\PathSegment
	 */
	public function setPathSegment($pathSegment)
	{
		$this->pathSegment = $pathSegment;

		return $this;
	}

	/**
	 * @return string|NULL
	 */
	public function getPathSegment()
	{
		return $this->pathSegment;
	}

	/**
	 * @param integer
	 * @return \Entity\Routing\PathSegment
	 */
	public function setType($type)
	{
		$this->type = $type;

		return $this;
	}

	/**
	 * @return integer|NULL
	 */
	public function getType()
	{
		return $this->type;
	}

	/**
	 * @param integer
	 * @return \Entity\Routing\PathSegment
	 */
	public function setEntityId($entityId)
	{
		$this->entityId = $entityId;

		return $this;
	}

	/**
	 * @return integer|NULL
	 */
	public function getEntityId()
	{
		return $this->entityId;
	}
}
