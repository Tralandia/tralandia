<?php

namespace Entities\Rental;

use Entities\Dictionary;
use Entities\Rental;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="rental_fulltext")
 */
class Fulltext extends \BaseEntity {

	/**
	 * @var Collection
	 * @ORM\ManyToMany(type="Rental")
	 */
	protected $rental;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Dictionary\Language")
	 */
	protected $language;

	/**
	 * @var text
	 * @ORM\ManyToMany(type="text")
	 */
	protected $value;


	public function __construct() {
		parent::__construct();

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
