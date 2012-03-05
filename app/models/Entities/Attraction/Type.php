<?php

namespace Entities\Attraction;

use Entities\Dictionary;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="attraction_type")
 */
class Type extends \BaseEntity {

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Dictionary\Phrase")
	 */
	protected $name;


	public function __construct() {
		parent::__construct();

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
