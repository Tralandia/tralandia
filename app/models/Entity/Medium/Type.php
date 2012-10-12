<?php

namespace Entity\Medium;

use Entity\Dictionary;
use Doctrine\ORM\Mapping as ORM;
use	Extras\Annotation as EA;

/**
 * @ORM\Entity()
 * @ORM\Table(name="medium_type")
 * @EA\Service(name="\Service\Medium\Type")
 * @EA\ServiceList(name="\Service\Medium\TypeList")
 * @EA\Primary(key="id", value="name")
 */
class Type extends \Entity\BaseEntityDetails {

	/**
	 * @var text
	 * @ORM\Column(type="text")
	 */
	protected $name;

	//@entity-generator-code --- NEMAZAT !!!

	/* ----------------------------- Methods ----------------------------- */		
	public function __construct()
	{
		parent::__construct();
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