<?php

namespace Entity\Location;

use Entity\Phrase;
use Doctrine\ORM\Mapping as ORM;
use	Extras\Annotation as EA;

/**
 * @ORM\Entity()
 * @ORM\Table(name="location_type", indexes={@ORM\index(name="slug", columns={"slug"})})
 *
 *
 */
class Type extends \Entity\BaseEntityDetails {


	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Entity\Phrase\Phrase", cascade={"persist", "remove"})
	 */
	protected $name;

	/**
	 * @var string
	 * @ORM\Column(type="string")
	 */
	protected $slug;

	/**
	 * primary types can have their own domain (easily) and can be parent to other types
	 * @var boolean
	 * @ORM\Column(name="`primary`", type="boolean")
	 */
	protected $primary = FALSE;

	/**
	 * @param string
	 * @return \Entity\Location\Type
	 */
	public function setSlug($slug)
	{
		$this->slug = \Nette\Utils\Strings::webalize($slug);

		return $this;
	}

	//@entity-generator-code --- NEMAZAT !!!

	/* ----------------------------- Methods ----------------------------- */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * @param \Entity\Phrase\Phrase
	 * @return \Entity\Location\Type
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
	 * @return string|NULL
	 */
	public function getSlug()
	{
		return $this->slug;
	}

	/**
	 * @param boolean
	 * @return \Entity\Location\Type
	 */
	public function setPrimary($primary)
	{
		$this->primary = $primary;

		return $this;
	}

	/**
	 * @return boolean|NULL
	 */
	public function getPrimary()
	{
		return $this->primary;
	}
}
