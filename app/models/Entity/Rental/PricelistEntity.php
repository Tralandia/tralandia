<?php

namespace Entity\Rental;

use Entity\Phrase;
use Doctrine\ORM\Mapping as ORM;

use    Extras\Annotation as EA;
use Extras\FileStorage;

use Nette\Http\FileUpload;

/**
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="rental_pricelist")
 * @EA\Primary(key="id", value="name")
 */
class Pricelist extends \Entity\BaseEntity
{

	/**
	 * @var text
	 * @ORM\Column(type="string")
	 */
	protected $name;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Rental")
	 * @ORM\JoinColumn(onDelete="CASCADE")
	 */
	protected $rental;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entity\Language")
	 */
	protected $language;

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


	//@entity-generator-code --- NEMAZAT !!!

	/* ----------------------------- Methods ----------------------------- */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * @param string
	 * @return \Entity\Rental\Pricelist
	 */
	public function setName($name)
	{
		$this->name = $name;

		return $this;
	}

	/**
	 * @return string|NULL
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @param \Entity\Rental\Rental
	 * @return \Entity\Rental\Pricelist
	 */
	public function setRental(\Entity\Rental\Rental $rental)
	{
		$this->rental = $rental;

		return $this;
	}

	/**
	 * @return \Entity\Rental\Pricelist
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
	 * @param \Entity\Language
	 * @return \Entity\Rental\Pricelist
	 */
	public function setLanguage(\Entity\Language $language)
	{
		$this->language = $language;

		return $this;
	}

	/**
	 * @return \Entity\Rental\Pricelist
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
	 * @return \Entity\Rental\Pricelist
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
	 * @return \Entity\Rental\Pricelist
	 */
	public function setOldUrl($oldUrl)
	{
		$this->oldUrl = $oldUrl;

		return $this;
	}

	/**
	 * @return \Entity\Rental\Pricelist
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
}
