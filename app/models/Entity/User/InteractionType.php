<?php

namespace Entity\User;

use Entity\Dictionary;
use Doctrine\ORM\Mapping as ORM;
use	Extras\Annotation as EA;

/**
 * @ORM\Entity()
 * @ORM\Table(name="user_interaction_type")
 * @EA\Service(name="\Service\User\InteractionType")
 * @EA\ServiceList(name="\Service\User\InteractionTypeList")
 * @EA\Primary(key="id", value="id")
 */
class InteractionType extends \Entity\BaseEntity {

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Entity\Dictionary\Phrase", cascade={"persist", "remove"})
	 */
	protected $name;


//@entity-generator-code <--- NEMAZAT !!!

	/* ----------------------------- Methods ----------------------------- */		
	public function __construct() {
		parent::__construct();
	}
		
	/**
	 * @param \Entity\Dictionary\Phrase
	 * @return \Entity\User\InteractionType
	 */
	public function setName(\Entity\Dictionary\Phrase $name) {
		$this->name = $name;

		return $this;
	}
		
	/**
	 * @return \Entity\Dictionary\Phrase|NULL
	 */
	public function getName() {
		return $this->name;
	}
}