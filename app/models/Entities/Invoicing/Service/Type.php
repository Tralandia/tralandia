<?php

namespace Entities\Invoicing\Service;

use Entities\Dictionary;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="invoicing_service_type")
 */
class Type extends \BaseEntity {

	/**
	 * @var string
	 * @ORM\ManyToMany(type="string", nullable=true)
	 */
	protected $nameTechnical;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Dictionary\Phrase")
	 */
	protected $name;


	public function __construct() {
		parent::__construct();

	}


	/**
	 * @param string $nameTechnical
	 * @return Type
	 */
	public function setNameTechnical($nameTechnical) {
		$this->nameTechnical = $nameTechnical;
		return $this;
	}


	/**
	 * @return string
	 */
	public function getNameTechnical() {
		return $this->nameTechnical;
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
