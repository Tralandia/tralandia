<?php

namespace Entities\Medium;

use Entities\Dictionary;
use Entities\Medium;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="medium_medium")
 */
class Medium extends \BaseEntityDetails {

	/**
	 * @var Collection
	 * @ORM\ManyToMany(type="Type")
	 */
	protected $type;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Dictionary\Phrase")
	 */
	protected $name;

	/**
	 * @var text
	 * @ORM\ManyToMany(type="text")
	 */
	protected $location;

	/**
	 * @var integer
	 * @ORM\ManyToMany(type="integer")
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
