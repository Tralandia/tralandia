<?php

namespace Entity\Location;

use Entity\Dictionary;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="location_type")
 */
class Type extends \Entity\BaseEntityDetails {

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

	/* ----------------------------- Methods ----------------------------- */


	public function __construct() {
		parent::__construct();
	}

	/**
	 * @param \Entity\Dictionary\Phrase
	 * @return \Entity\Location\Type
	 */
	public function setName(\Entity\Dictionary\Phrase $name) {
		$this->name = $name;

		return $this;
	}

	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection of \Entity\Dictionary\Phrase
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @param slug
	 * @return \Entity\Location\Type
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