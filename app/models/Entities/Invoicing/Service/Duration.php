<?php

namespace Entities\Invoicing\Service;

use Entities\Dictionary;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="invoicing_service_duration")
 */
class Duration extends \BaseEntity {

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Dictionary\Phrase")
	 */
	protected $name;

	/**
	 * @var datetime
	 * @ORM\ManyToMany(type="datetime")
	 */
	protected $duration;

	/**
	 * @var integer
	 * @ORM\ManyToMany(type="integer")
	 */
	protected $sort;


	public function __construct() {

	}


	/**
	 * @param Dictionary\Phrase $name
	 * @return Duration
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
	 * @param datetime $duration
	 * @return Duration
	 */
	public function setDuration($duration) {
		$this->duration = $duration;
		return $this;
	}


	/**
	 * @return datetime
	 */
	public function getDuration() {
		return $this->duration;
	}


	/**
	 * @param integer $sort
	 * @return Duration
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
