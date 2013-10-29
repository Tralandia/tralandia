<?php

namespace Entity\Medium;

use Entity\Phrase;
use Doctrine\ORM\Mapping as ORM;
use	Extras\Annotation as EA;

/**
 * @ORM\Entity()
 * @ORM\Table(name="medium_type")
 *
 *
 */
class Type extends \Entity\BaseEntityDetails {

	/**
	 * @var string
	 * @ORM\Column(type="string")
	 */
	protected $slug;

	/**
	 * @var string
	 * @ORM\Column(type="string")
	 */
	protected $name;

	/**
	 * @param string
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
	 * @return string|NULL
	 */
	public function getSlug()
	{
		return $this->slug;
	}

	/**
	 * @param string
	 * @return \Entity\Medium\Type
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
}
