<?php

namespace Entity;

use Entity\Location;
use Doctrine\ORM\Mapping as ORM;
use	Extras\Annotation as EA;

/**
 * @ORM\Entity()
 * @ORM\Table(name="page", indexes={@ORM\index(name="destination", columns={"destination"})})
 * @EA\Primary(key="id", value="destination")
 */
class Page extends \Entity\BaseEntity {

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $type;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $hash;

	/**
	 * @var string
	 * @ORM\Column(type="string")
	 * example: :Module:Presenter:action
	 */
	protected $destination;

	/**
	 * @var json
	 * @ORM\Column(type="json", nullable=true)
	 */
	protected $parameters;

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Entity\Phrase\Phrase", cascade={"persist", "remove"})
	 */
	protected $titlePattern;

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Entity\Phrase\Phrase", cascade={"persist", "remove"})
	 */
	protected $h1Pattern;

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Entity\Phrase\Phrase", cascade={"persist", "remove"})
	 */
	protected $genericContent;

	/**
	 * @var Boolean
	 * @ORM\Column(type="boolean")
	 */
	protected $generatePathSegment = FALSE;

	//@entity-generator-code --- NEMAZAT !!!

	/* ----------------------------- Methods ----------------------------- */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * @param string
	 * @return \Entity\Page
	 */
	public function setType($type)
	{
		$this->type = $type;

		return $this;
	}

	/**
	 * @return \Entity\Page
	 */
	public function unsetType()
	{
		$this->type = NULL;

		return $this;
	}

	/**
	 * @return string|NULL
	 */
	public function getType()
	{
		return $this->type;
	}

	/**
	 * @param string
	 * @return \Entity\Page
	 */
	public function setHash($hash)
	{
		$this->hash = $hash;

		return $this;
	}

	/**
	 * @return \Entity\Page
	 */
	public function unsetHash()
	{
		$this->hash = NULL;

		return $this;
	}

	/**
	 * @return string|NULL
	 */
	public function getHash()
	{
		return $this->hash;
	}

	/**
	 * @param string
	 * @return \Entity\Page
	 */
	public function setDestination($destination)
	{
		$this->destination = $destination;

		return $this;
	}

	/**
	 * @return string|NULL
	 */
	public function getDestination()
	{
		return $this->destination;
	}

	/**
	 * @param json
	 * @return \Entity\Page
	 */
	public function setParameters($parameters)
	{
		$this->parameters = $parameters;

		return $this;
	}

	/**
	 * @return \Entity\Page
	 */
	public function unsetParameters()
	{
		$this->parameters = NULL;

		return $this;
	}

	/**
	 * @return json|NULL
	 */
	public function getParameters()
	{
		return $this->parameters;
	}

	/**
	 * @param \Entity\Phrase\Phrase
	 * @return \Entity\Page
	 */
	public function setTitlePattern(\Entity\Phrase\Phrase $titlePattern)
	{
		$this->titlePattern = $titlePattern;

		return $this;
	}

	/**
	 * @return \Entity\Phrase\Phrase|NULL
	 */
	public function getTitlePattern()
	{
		return $this->titlePattern;
	}

	/**
	 * @param \Entity\Phrase\Phrase
	 * @return \Entity\Page
	 */
	public function setH1Pattern(\Entity\Phrase\Phrase $h1Pattern)
	{
		$this->h1Pattern = $h1Pattern;

		return $this;
	}

	/**
	 * @return \Entity\Phrase\Phrase|NULL
	 */
	public function getH1Pattern()
	{
		return $this->h1Pattern;
	}

	/**
	 * @param \Entity\Phrase\Phrase
	 * @return \Entity\Page
	 */
	public function setGenericContent(\Entity\Phrase\Phrase $genericContent)
	{
		$this->genericContent = $genericContent;

		return $this;
	}

	/**
	 * @return \Entity\Phrase\Phrase|NULL
	 */
	public function getGenericContent()
	{
		return $this->genericContent;
	}


	/**
	 * @return boolean
	 */
	public function getGeneratePathSegment()
	{
		return $this->generatePathSegment;
	}


	/**
	 * @param boolean $generatePathSegment
	 */
	public function setGeneratePathSegment($generatePathSegment)
	{
		$this->generatePathSegment = $generatePathSegment;
	}
}
