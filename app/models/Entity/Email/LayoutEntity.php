<?php

namespace Entity\Email;

use Doctrine\ORM\Mapping as ORM;
use Extras\Annotation as EA;

/**
 * @ORM\Entity()
 * @ORM\Table(name="email_layout")
 *
 */
class Layout extends \Entity\BaseEntity {

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true, unique=true)
	 */
	protected $slug;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $name;

	/**
	 * @var string
	 * @ORM\Column(type="text")
	 */
	protected $html;

	/**
	 * @param string
	 * @return \Entity\Rental\Rental
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
	 * @return \Entity\Email\Layout
	 */
	public function unsetSlug()
	{
		$this->slug = NULL;

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
	 * @param string
	 * @return \Entity\Email\Layout
	 */
	public function setName($name)
	{
		$this->name = $name;

		return $this;
	}

	/**
	 * @return \Entity\Email\Layout
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
	 * @return \Entity\Email\Layout
	 */
	public function setHtml($html)
	{
		$this->html = $html;

		return $this;
	}

	/**
	 * @return string|NULL
	 */
	public function getHtml()
	{
		return $this->html;
	}
}
