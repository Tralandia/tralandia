<?php

namespace Entity\Log;

use Doctrine\ORM\Mapping as ORM;
use	Extras\Annotation as EA;

/**
 * @ORM\Entity()
 * @ORM\Table(name="log_system")
 *
 */
class System extends \Entity\BaseEntityDetails {

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
	 * @var text
	 * @ORM\Column(type="text", nullable=true)
	 */
	protected $comment;

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
	 * @return \Entity\Log\System
	 */
	public function setName($name)
	{
		$this->name = $name;

		return $this;
	}

	/**
	 * @return \Entity\Log\System
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
	 * @param string
	 * @return \Entity\Log\System
	 */
	public function setComment($comment)
	{
		$this->comment = $comment;

		return $this;
	}

	/**
	 * @return \Entity\Log\System
	 */
	public function unsetComment()
	{
		$this->comment = NULL;

		return $this;
	}

	/**
	 * @return string|NULL
	 */
	public function getComment()
	{
		return $this->comment;
	}
}
