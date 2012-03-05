<?php

namespace Entities\Rental;

use Entities\Dictionary;
use Doctrine\Common\Collections\Collection
use Doctrine\Common\Collections\ArrayCollection


/**
 * @Entity()
 * @Table(name="rental_type")
 */
class Type extends \BaseEntity {

	/**
	 * @var Collection
	 * @ManyToMany(targetEntity="Dictionary\Phrase")
	 */
	protected $name;


	public function __construct() {

	}


	/**
	 * @param Dictionary\Phrase $name
	 * @return Type
	 */
	public function setName(Dictionary\Phrase  $name) {
		$this->name = $name;
		return $this;
	}


	/**
	 * @return Dictionary\Phrase
	 */
	public function getName() {
		return $this->name;
	}

}
