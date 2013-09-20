<?php

namespace Entity\Rental;

use Entity\Phrase;
use Doctrine\ORM\Mapping as ORM;
use    Extras\Annotation as EA;

/**
 * @ORM\Entity()
 * @ORM\Table(name="rental_roomtype")
 *
 *
 */
class RoomType extends \Entity\BaseEntity
{

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Entity\Phrase\Phrase", cascade={"persist", "remove"})
	 */
	protected $name;

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Entity\Phrase\Phrase", cascade={"persist", "remove"})
	 */
	protected $textPriceFor;

	/**
	 * @var string
	 * @ORM\Column(type="string")
	 */
	protected $slug;


	/**
	 * @param string
	 *
	 * @return \Entity\Rental\RoomType
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
	 * @return \Entity\Rental\RoomType
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
	 * @param \Entity\Phrase\Phrase
	 * @return \Entity\Rental\RoomType
	 */
	public function setTextPriceFor(\Entity\Phrase\Phrase $textPriceFor)
	{
		$this->textPriceFor = $textPriceFor;

		return $this;
	}

	/**
	 * @return \Entity\Phrase\Phrase|NULL
	 */
	public function getTextPriceFor()
	{
		return $this->textPriceFor;
	}

	/**
	 * @return string|NULL
	 */
	public function getSlug()
	{
		return $this->slug;
	}
}
