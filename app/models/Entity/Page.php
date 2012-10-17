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
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Entity\Phrase\Phrase", cascade={"persist", "remove"})
	 */
	protected $name;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $type;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 * example: :Module:Presenter:Action
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
	protected $genericContent;

	//@entity-generator-code --- NEMAZAT !!!

	/* ----------------------------- Methods ----------------------------- */		
	public function __construct()
	{
		parent::__construct();
	}
		
	/**
	 * @param \Entity\Phrase\Phrase
	 * @return \Entity\Page
	 */
	public function setName(\Entity\Phrase\Phrase $name)
	{
		$this->name = $name;

		return $this;
	}
		
	/**
	 * @return \Entity\Phrase\Phrase|NULL
	 */
	public function getName()
	{
		return $this->name;
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
	public function setDestination($destination)
	{
		$this->destination = $destination;

		return $this;
	}
		
	/**
	 * @return \Entity\Page
	 */
	public function unsetDestination()
	{
		$this->destination = NULL;

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
}