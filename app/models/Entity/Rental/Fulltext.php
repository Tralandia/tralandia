<?php

namespace Entity\Rental;

use Entity\Dictionary;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="rental_fulltext")
 */
class Fulltext extends \Entity\BaseEntity {

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Rental", inversedBy="fulltexts")
	 */
	protected $rental;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entity\Dictionary\Language")
	 */
	protected $language;

	/**
	 * @var text
	 * @ORM\Column(type="text")
	 */
	protected $value;

	




//@entity-generator-code <--- NEMAZAT !!!

	/* ----------------------------- Methods ----------------------------- */		
	public function __construct() {
		parent::__construct();
	}
		
	/**
	 * @param \Entity\Rental\Rental
	 * @return \Entity\Rental\Fulltext
	 */
	public function setRental(\Entity\Rental\Rental $rental) {
		$this->rental = $rental;

		return $this;
	}
		
	/**
	 * @return \Entity\Rental\Fulltext
	 */
	public function unsetRental() {
		$this->rental = NULL;

		return $this;
	}
		
	/**
	 * @return \Entity\Rental\Rental|NULL
	 */
	public function getRental() {
		return $this->rental;
	}
		
	/**
	 * @param \Entity\Dictionary\Language
	 * @return \Entity\Rental\Fulltext
	 */
	public function setLanguage(\Entity\Dictionary\Language $language) {
		$this->language = $language;

		return $this;
	}
		
	/**
	 * @return \Entity\Rental\Fulltext
	 */
	public function unsetLanguage() {
		$this->language = NULL;

		return $this;
	}
		
	/**
	 * @return \Entity\Dictionary\Language|NULL
	 */
	public function getLanguage() {
		return $this->language;
	}
		
	/**
	 * @param string
	 * @return \Entity\Rental\Fulltext
	 */
	public function setValue($value) {
		$this->value = $value;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getValue() {
		return $this->value;
	}
}