<?php

namespace Entity\Rental;

use Entity\Phrase;
use Entity\Attraction;
use Doctrine\ORM\Mapping as ORM;

use	Extras\Annotation as EA;
use Extras\FileStorage;

use Nette\Http\FileUpload;

/**
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="rental_image", indexes={@ORM\index(name="oldUrl", columns={"oldUrl"}), @ORM\index(name="sort", columns={"sort"})})
 * @EA\Primary(key="id", value="name")
 */
class Image extends \Entity\BaseEntity {

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Entity\Phrase\Phrase", cascade={"persist", "remove"})
	 */
	protected $name;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Rental", inversedBy="images")
	 */
	protected $rental;

	/**
	 * @var text
	 * @ORM\Column(type="string")
	 */
	protected $filePath;

	/**
	 * @var text
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $oldUrl;

	/**
	 * @var integer
	 * @ORM\Column(type="integer", nullable=true)
	 */
	protected $sort = 0;

	//@entity-generator-code --- NEMAZAT !!!

	/* ----------------------------- Methods ----------------------------- */		
	public function __construct()
	{
		parent::__construct();
	}
		
	/**
	 * @param \Entity\Phrase\Phrase
	 * @return \Entity\Rental\Image
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
	 * @param \Entity\Rental\Rental
	 * @return \Entity\Rental\Image
	 */
	public function setRental(\Entity\Rental\Rental $rental)
	{
		$this->rental = $rental;

		return $this;
	}
		
	/**
	 * @return \Entity\Rental\Image
	 */
	public function unsetRental()
	{
		$this->rental = NULL;

		return $this;
	}
		
	/**
	 * @return \Entity\Rental\Rental|NULL
	 */
	public function getRental()
	{
		return $this->rental;
	}
		
	/**
	 * @param string
	 * @return \Entity\Rental\Image
	 */
	public function setFilePath($filePath)
	{
		$this->filePath = $filePath;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getFilePath()
	{
		return $this->filePath;
	}
		
	/**
	 * @param string
	 * @return \Entity\Rental\Image
	 */
	public function setOldUrl($oldUrl)
	{
		$this->oldUrl = $oldUrl;

		return $this;
	}
		
	/**
	 * @return \Entity\Rental\Image
	 */
	public function unsetOldUrl()
	{
		$this->oldUrl = NULL;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getOldUrl()
	{
		return $this->oldUrl;
	}
		
	/**
	 * @param integer
	 * @return \Entity\Rental\Image
	 */
	public function setSort($sort)
	{
		$this->sort = $sort;

		return $this;
	}
		
	/**
	 * @return \Entity\Rental\Image
	 */
	public function unsetSort()
	{
		$this->sort = NULL;

		return $this;
	}
		
	/**
	 * @return integer|NULL
	 */
	public function getSort()
	{
		return $this->sort;
	}
}