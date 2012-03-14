<?php

namespace Entities\Dictionary;

use Entities\Dictionary;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="dictionary_type")
 */
class Type extends \BaseEntity {

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $name;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $entityName;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $entityAttribute;

	/**
	 * @var integer
	 * @ORM\Column(type="integer")
	 */
	protected $translationLevelRequirement;

	/**
	 * @var boolean
	 * @ORM\Column(type="boolean")
	 */
	protected $multitranslationRequired;

	/**
	 * @var boolean
	 * @ORM\Column(type="boolean")
	 */
	protected $genderNumberRequired;

	/**
	 * @var boolean
	 * @ORM\Column(type="boolean")
	 */
	protected $locativeRequired;

	/**
	 * @var boolean
	 * @ORM\Column(type="boolean")
	 */
	protected $positionRequired;

	/**
	 * @var boolean
	 * @ORM\Column(type="boolean")
	 */
	protected $webalizedRequired;

}