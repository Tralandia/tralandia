<?php

namespace Entity\Rental;

use Entity\Phrase;
use Doctrine\ORM\Mapping as ORM;
use Extras\Annotation as EA;

/**
 * @ORM\Entity
 * @ORM\Table(name="rental_amenity")
 *
 *
 */
class Amenity extends \Entity\BaseEntityDetails
{

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="AmenityType", inversedBy="amenities")
	 * @ORM\JoinColumn(onDelete="CASCADE")
	 */
	protected $type;

	/**
	 * @var string
	 * @ORM\Column(type="string")
	 */
	protected $slug;

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Entity\Phrase\Phrase", cascade={"persist", "remove"})
	 * @ORM\JoinColumn(onDelete="CASCADE")
	 */
	protected $name;

	/**
	 * @var boolean
	 * @ORM\Column(type="boolean")
	 */
	protected $important = FALSE;

	/**
	 * @var integer
	 * @ORM\Column(type="integer")
	 */
	protected $sorting = 10000;


	/**
	 * @param string
	 *
	 * @return \Entity\Rental\AmenityType
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
	 * @param \Entity\Rental\AmenityType
	 * @return \Entity\Rental\Amenity
	 */
	public function setType(\Entity\Rental\AmenityType $type)
	{
		$this->type = $type;

		return $this;
	}

	/**
	 * @return \Entity\Rental\Amenity
	 */
	public function unsetType()
	{
		$this->type = NULL;

		return $this;
	}

	/**
	 * @return \Entity\Rental\AmenityType|NULL
	 */
	public function getType()
	{
		return $this->type;
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
	 * @return \Entity\Rental\Amenity
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
	 * @return \Entity\Rental\Amenity
	 */
	public function setImportant($important)
	{
		$this->important = $important;

		return $this;
	}

	/**
	 * @return boolean|NULL
	 */
	public function getImportant()
	{
		return $this->important;
	}

	/**
	 * @param integer
	 * @return \Entity\Rental\Amenity
	 */
	public function setSorting($sorting)
	{
		$this->sorting = $sorting;

		return $this;
	}

	/**
	 * @return integer|NULL
	 */
	public function getSorting()
	{
		return $this->sorting;
	}
}
