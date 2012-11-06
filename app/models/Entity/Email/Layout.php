<?php

namespace Entity\Email;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="email_layout")
 */
class Layout extends \Entity\BaseEntity {

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $name;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $file;


	//@entity-generator-code --- NEMAZAT !!!

	/* ----------------------------- Methods ----------------------------- */		
	public function __construct()
	{
		parent::__construct();
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
	public function setFile($file)
	{
		$this->file = $file;

		return $this;
	}
		
	/**
	 * @return \Entity\Email\Layout
	 */
	public function unsetFile()
	{
		$this->file = NULL;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getFile()
	{
		return $this->file;
	}
}