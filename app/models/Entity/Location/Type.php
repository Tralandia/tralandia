<?php

namespace Entity\Location;

use Entity\Dictionary;
use Doctrine\ORM\Mapping as ORM;
use	Extras\Annotation as EA;

/**
 * @ORM\Entity()
 * @ORM\Entity(repositoryClass="\BaseRepository")
 * @ORM\Table(name="location_type", indexes={@ORM\index(name="slug", columns={"slug"})})
 * @EA\Service(name="\Service\Location\Type")
 * @EA\ServiceList(name="\Service\Location\TypeList")
 * @EA\Primary(key="id", value="name")
 */
class Type extends \Entity\BaseEntityDetails {

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Entity\Dictionary\Phrase", cascade={"persist", "remove"})
	 */
	protected $name;
	
	/**
	 * @var slug
	 * @ORM\Column(type="slug")
	 */
	protected $slug;

	/**
	 * @var boolean
	 * @ORM\Column(type="boolean")
	 * primary types can have their own domain (easily) and can be parent to other types
	 */
	protected $primary;

	//@entity-generator-code --- NEMAZAT !!!

	/* ----------------------------- Methods ----------------------------- */		
	public function __construct()
	{
		parent::__construct();
	}
		
	/**
	 * @param \Entity\Dictionary\Phrase
	 * @return \Entity\Location\Type
	 */
	public function setName(\Entity\Dictionary\Phrase $name)
	{
		$this->name = $name;

		return $this;
	}
		
	/**
	 * @return \Entity\Dictionary\Phrase|NULL
	 */
	public function getName()
	{
		return $this->name;
	}
		
	/**
	 * @param slug
	 * @return \Entity\Location\Type
	 */
	public function setSlug($slug)
	{
		$this->slug = $slug;

		return $this;
	}
		
	/**
	 * @return slug|NULL
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