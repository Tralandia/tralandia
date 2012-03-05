<?php

namespace Invoicing\Service;

use Dictionary;
use Doctrine\Common\Collections\Collection
use Doctrine\Common\Collections\ArrayCollection


/**
 * @Entity()
 * @Table(name="invoicing_service_duration")
 */
class Duration extends \BaseEntity {

	/**
	 * @var Collection
	 * @ManyToMany(targetEntity="Dictionary\Phrase")
	 */
	protected $name;

	/**
	 * @var datetime
	 * @Column(type="datetime")
	 */
	protected $duration;

	/**
	 * @var integer
	 * @Column(type="integer")
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
