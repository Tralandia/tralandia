<?php

namespace Entities\Emailing;

use Entities\Dictionary;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="emailing_type")
 */
class Type extends \BaseEntity {

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Dictionary\Phrase")
	 */
	protected $name;

	/**
	 * @var boolean
	 * @ORM\ManyToMany(type="boolean")
	 */
	protected $translationsRequired;


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
	 * @param boolean $translationsRequired
	 * @return Type
	 */
	public function setTranslationsRequired($translationsRequired) {
		$this->translationsRequired = $translationsRequired;
		return $this;
	}


	/**
	 * @return boolean
	 */
	public function getTranslationsRequired() {
		return $this->translationsRequired;
	}

}
