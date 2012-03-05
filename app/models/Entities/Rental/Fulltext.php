<?php

namespace Rental;

use Dictionary;
use Rental;
use Doctrine\Common\Collections\Collection
use Doctrine\Common\Collections\ArrayCollection


/**
 * @Entity()
 * @Table(name="rental_fulltext")
 */
class Fulltext extends \BaseEntity {

	/**
	 * @var Collection
	 * @Column(type="Rental")
	 */
	protected $rental;

	/**
	 * @var Collection
	 * @ManyToMany(targetEntity="Dictionary\Language")
	 */
	protected $language;

	/**
	 * @var text
	 * @Column(type="text")
	 */
	protected $value;


	public function __construct() {

	}


	/**
	 * @param Rental $rental
	 * @return Fulltext
	 */
	public function setRental(Rental  $rental) {
		$this->rental = $rental;
		return $this;
	}


	/**
	 * @return Rental
	 */
	public function getRental() {
		return $this->rental;
	}


	/**
	 * @param Dictionary\Language $language
	 * @return Fulltext
	 */
	public function setLanguage(Dictionary\Language  $language) {
		$this->language = $language;
		return $this;
	}


	/**
	 * @return Dictionary\Language
	 */
	public function getLanguage() {
		return $this->language;
	}


	/**
	 * @param text $value
	 * @return Fulltext
	 */
	public function setValue($value) {
		$this->value = $value;
		return $this;
	}


	/**
	 * @return text
	 */
	public function getValue() {
		return $this->value;
	}

}
