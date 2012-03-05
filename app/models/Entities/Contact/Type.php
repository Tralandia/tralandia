<?php

namespace Entities\Contact;

use Entities\Dictionary;
use Doctrine\Common\Collections\Collection
use Doctrine\Common\Collections\ArrayCollection


/**
 * @Entity()
 * @Table(name="contact_type")
 */
class Type extends \BaseEntity {

	/**
	 * @var Collection
	 * @OneToOne(targetEntity="Dictionary\Phrase")
	 */
	protected $name;

	/**
	 * @var string
	 * @Column(type="string", nullable=true)
	 */
	protected $class;


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


	/**
	 * @param string $class
	 * @return Type
	 */
	public function setClass($class) {
		$this->class = $class;
		return $this;
	}


	/**
	 * @return string
	 */
	public function getClass() {
		return $this->class;
	}

}
