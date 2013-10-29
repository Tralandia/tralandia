<?php

namespace Entity\Log;

use Doctrine\ORM\Mapping as ORM;
use	Extras\Annotation as EA;

/**
 * @ORM\Entity()
 * @ORM\Table(name="log_changetype")
 *
 */
class ChangeType extends \Entity\BaseEntity {

	/**
	 * @var string
	 * @ORM\Column(type="string")
	 */
	protected $slug;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $name;

	/**
	 * @var boolean
	 * @ORM\Column(type="boolean")
	 */
	protected $important;

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
	 * @return \Entity\Log\ChangeType
	 */
	public function setName($name)
	{
		$this->name = $name;

		return $this;
	}

	/**
	 * @return \Entity\Log\ChangeType
	 */
	public function unsetName()
	{
		$this->name = NULL;

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
	 * @param boolean
	 * @return \Entity\Log\ChangeType
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
}
