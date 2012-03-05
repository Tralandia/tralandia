<?php

namespace Medium;

use Dictionary;
use Medium;
use Doctrine\Common\Collections\Collection
use Doctrine\Common\Collections\ArrayCollection


/**
 * @Entity()
 * @Table(name="medium_medium")
 */
class Medium extends \BaseEntityDetails {

	/**
	 * @var Collection
	 * @Column(type="Type")
	 */
	protected $type;

	/**
	 * @var Collection
	 * @ManyToMany(targetEntity="Dictionary\Phrase")
	 */
	protected $name;

	/**
	 * @var text
	 * @Column(type="text")
	 */
	protected $location;

	/**
	 * @var integer
	 * @Column(type="integer")
	 */
	protected $sort;


	public function __construct() {

	}


	/**
	 * @param Type $type
	 * @return Medium
	 */
	public function setType(Type  $type) {
		$this->type = $type;
		return $this;
	}


	/**
	 * @return Type
	 */
	public function getType() {
		return $this->type;
	}


	/**
	 * @param Dictionary\Phrase $name
	 * @return Medium
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
	 * @param text $location
	 * @return Medium
	 */
	public function setLocation($location) {
		$this->location = $location;
		return $this;
	}


	/**
	 * @return text
	 */
	public function getLocation() {
		return $this->location;
	}


	/**
	 * @param integer $sort
	 * @return Medium
	 */
	public function setSort($sort) {
		$this->sort = $sort;
		return $this;
	}


	/**
	 * @return integer
	 */
	public function getSort() {
		return $this->sort;
	}

}
