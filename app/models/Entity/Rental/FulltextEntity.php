<?php

namespace Entity\Rental;

use Entity\Phrase;
use Doctrine\ORM\Mapping as ORM;
use    Extras\Annotation as EA;

/**
 * @ORM\Entity()
 * @ORM\Table(name="rental_fulltext")
 * @EA\Primary(key="id", value="value")
 */
class Fulltext extends \Entity\BaseEntity
{

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Rental", inversedBy="fulltexts")
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
	 * @ORM\Column(type="text")
	 */
	protected $value;


	//@entity-generator-code --- NEMAZAT !!!

	/* ----------------------------- Methods ----------------------------- */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * @param \Entity\Rental\Rental
	 * @return \Entity\Rental\Fulltext
	 */
	public function setRental(\Entity\Rental\Rental $rental)
	{
		$this->rental = $rental;

		return $this;
	}

	/**
	 * @return \Entity\Rental\Fulltext
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
	 * @return \Entity\Rental\Fulltext
	 */
	public function setLanguage(\Entity\Language $language)
	{
		$this->language = $language;

		return $this;
	}

	/**
	 * @return \Entity\Rental\Fulltext
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
	 * @return \Entity\Rental\Fulltext
	 */
	public function setValue($value)
	{
		$this->value = $value;

		return $this;
	}

	/**
	 * @return string|NULL
	 */
	public function getValue()
	{
		return $this->value;
	}
}
