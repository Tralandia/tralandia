<?php

namespace Entities\Rental\Amenity;

use Entities\Dictionary;
use Entities\Rental;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="rental_amenity_amenity")
 */
class Amenity extends \BaseEntity {

	/**
	 * @var Collection
	 * @ORM\ManyToMany(type="Group")
	 */
	protected $group;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Dictionary\Phrase")
	 */
	protected $name;


	public function __construct() {
		parent::__construct();

	}


	/**
	 * @param Group $group
	 * @return Amenity
	 */
	public function setGroup(Group  $group) {
		$this->group = $group;
		return $this;
	}


	/**
	 * @return Group
	 */
	public function getGroup() {
		return $this->group;
	}


	/**
	 * @param Dictionary\Phrase $name
	 * @return Amenity
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
