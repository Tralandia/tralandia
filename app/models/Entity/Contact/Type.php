<?php

namespace Entity\Contact;

use Entity\Dictionary;
use Doctrine\ORM\Mapping as ORM;
use	Extras\Annotation as EA;

/**
 * @ORM\Entity()
 * @ORM\Table(name="contact_type", indexes={@ORM\index(name="slug", columns={"slug"})})
 * @EA\Service(name="\Service\Contact\Type")
 * @EA\ServiceList(name="\Service\Contact\TypeList")
 * @EA\Primary(key="id", value="value")
 */
class Type extends \Entity\BaseEntity {

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Entity\Dictionary\Phrase", cascade={"persist", "remove"})
	 */
	protected $name;

	/**
	 * @var slug
	 * @ORM\Column(type="slug")
	 */
	protected $slug;

	




















//@entity-generator-code <--- NEMAZAT !!!

	/* ----------------------------- Methods ----------------------------- */		
	public function __construct() {
		parent::__construct();
	}
		
	/**
	 * @param \Entity\Dictionary\Phrase
	 * @return \Entity\Contact\Type
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
		
	/**
	 * @param slug
	 * @return \Entity\Contact\Type
	 */
	public function setSlug($slug) {
		$this->slug = $slug;

		return $this;
	}
		
	/**
	 * @return slug|NULL
	 */
	public function getSlug() {
		return $this->slug;
	}
}