<?php

namespace Entity\Email;

use Doctrine\ORM\Mapping as ORM;
use	Extras\Annotation as EA;

/**
 * @ORM\Entity()
 * @ORM\Table(name="email_templatetype")
 * @EA\Primary(key="id", value="name")
 */
class TemplateType extends \Entity\BaseEntity {

	/**
	 * @var string
	 * @ORM\Column(type="string")
	 */
	protected $name;

	/**
	 * @var json
	 * @ORM\Column(type="json", nullable=true)
	 */
	protected $availableVariables;

									//@entity-generator-code --- NEMAZAT !!!

	/* ----------------------------- Methods ----------------------------- */		
	public function __construct()
	{
		parent::__construct();
	}
		
	/**
	 * @param string
	 * @return \Entity\Email\TemplateType
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
	 * @param json
	 * @return \Entity\Email\TemplateType
	 */
	public function setAvailableVariables($availableVariables)
	{
		$this->availableVariables = $availableVariables;

		return $this;
	}
		
	/**
	 * @return \Entity\Email\TemplateType
	 */
	public function unsetAvailableVariables()
	{
		$this->availableVariables = NULL;

		return $this;
	}
		
	/**
	 * @return json|NULL
	 */
	public function getAvailableVariables()
	{
		return $this->availableVariables;
	}
}