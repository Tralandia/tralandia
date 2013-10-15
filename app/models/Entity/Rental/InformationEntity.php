<?php

namespace Entity\Rental;

use Entity\Phrase;
use Doctrine\ORM\Mapping as ORM;
use    Extras\Annotation as EA;

/**
 * @ORM\Entity()
 * @ORM\Table(name="rental_information")
 *
 */
class Information extends \Entity\BaseEntityDetails
{

	/**
	 * @var string
	 * @ORM\Column(type="string")
	 */
	protected $slug;

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Entity\Phrase\Phrase", cascade={"persist", "remove"})
	 */
	protected $name;

	/**
	 * @var boolean
	 * @ORM\Column(type="boolean")
	 */
	protected $compulsory = FALSE;

	//@entity-generator-code --- NEMAZAT !!!

	/* ----------------------------- Methods ----------------------------- */
	public function __construct()
	{
		parent::__construct();

	}

	/**
	 * @param string
	 * @return \Entity\Rental\Information
	 */
	public function setSlug($slug)
	{
		$this->slug = $slug;

		return $this;
	}

	/**
	 * @return string|NULL
	 */
	public function getSlug()
	{
		return $this->slug;
	}

	/**
	 * @param \Entity\Phrase\Phrase
	 * @return \Entity\Rental\Information
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
	 * @param boolean
	 * @return \Entity\Rental\Information
	 */
	public function setCompulsory($compulsory)
	{
		$this->compulsory = $compulsory;

		return $this;
	}

	/**
	 * @return boolean|NULL
	 */
	public function getCompulsory()
	{
		return $this->compulsory;
	}

}
