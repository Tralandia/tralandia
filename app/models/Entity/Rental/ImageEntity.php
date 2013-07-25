<?php

namespace Entity\Rental;

use Entity\Phrase;
use Doctrine\ORM\Mapping as ORM;

use Extras\Annotation as EA;
use Extras\FileStorage;

use Nette\Http\FileUpload;

/**
 * @ORM\Entity()
 * @ORM\Table(name="rental_image", indexes={@ORM\index(name="sort", columns={"sort"})})
 * @EA\Primary(key="id", value="name")
 */
class Image extends \Entity\BaseEntity
{

	const ORIGINAL = 'original';

	const MEDIUM = 'medium';

	const EXTENSION = 'jpeg';

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Rental", inversedBy="images")
	 * @ORM\JoinColumn(onDelete="CASCADE")
	 */
	protected $rental;

	/**
	 * @var text
	 * @ORM\Column(type="string")
	 */
	protected $filePath;

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
