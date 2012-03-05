<?php

namespace Dictionary;

use Dictionary;
use Doctrine\Common\Collections\Collection
use Doctrine\Common\Collections\ArrayCollection


/**
 * @Entity()
 * @Table(name="dictionary_quality")
 */
class Quality extends \BaseEntity {

	/**
	 * @var Collection
	 * @Column(type="Phrase")
	 */
	protected $name;


	public function __construct() {

	}


	/**
	 * @param Phrase $name
	 * @return Quality
	 */
	public function setName(Phrase  $name) {
		$this->name = $name;
		return $this;
	}


	/**
	 * @return Phrase
	 */
	public function getName() {
		return $this->name;
	}

}
