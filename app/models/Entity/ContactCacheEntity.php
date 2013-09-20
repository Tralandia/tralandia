<?php

namespace Entity;

use Entity\Phrase;
use Doctrine\ORM\Mapping as ORM;
use	Extras\Annotation as EA;

/**
 * @ORM\Entity()
 * @ORM\Table(name="contact_cache",
 * 			indexes={
 * 				@ORM\index(name="entityName", columns={"entityName"}),
 * 				@ORM\index(name="entityId", columns={"entityId"}),
 * 				@ORM\index(name="type", columns={"type"}),
 * 				@ORM\index(name="value", columns={"value"}),
 * 		  	}
 * 		)
 *
 */
class ContactCache extends \Entity\BaseEntity {

	/**
	 * @var string
	 * @ORM\Column(type="string")
	 */
	protected $entityName;

	/**
	 * @var string
	 * @ORM\Column(type="string")
	 */
	protected $entityId;

	/**
	 * @var string
	 * @ORM\Column(type="string")
	 */
	protected $type;

	/**
	 * @var string
	 * @ORM\Column(type="string")
	 */
	protected $value;


								//@entity-generator-code --- NEMAZAT !!!

	/* ----------------------------- Methods ----------------------------- */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * @param string
	 * @return \Entity\ContactCache
	 */
	public function setEntityName($entityName)
	{
		$this->entityName = $entityName;

		return $this;
	}

	/**
	 * @return string|NULL
	 */
	public function getEntityName()
	{
		return $this->entityName;
	}

	/**
	 * @param string
	 * @return \Entity\ContactCache
	 */
	public function setEntityId($entityId)
	{
		$this->entityId = $entityId;

		return $this;
	}

	/**
	 * @return string|NULL
	 */
	public function getEntityId()
	{
		return $this->entityId;
	}

	/**
	 * @param string
	 * @return \Entity\ContactCache
	 */
	public function setType($type)
	{
		$this->type = $type;

		return $this;
	}

	/**
	 * @return string|NULL
	 */
	public function getType()
	{
		return $this->type;
	}

	/**
	 * @param string
	 * @return \Entity\ContactCache
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
