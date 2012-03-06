<?php

namespace Entities\Dictionary;

use Entities\Dictionary;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="dictionary_language")
 */
class Language extends \BaseEntityDetails {

	/**
	 * @var Collection
	 * @ORM\OneToOne(type="Phrase")
	 */
	protected $name;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $iso;

	/**
	 * @var boolean
	 * @ORM\Column(type="boolean")
	 */
	protected $supported;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $defaultCollation;

	/**
	 * @var json
	 * @ORM\Column(type="json")
	 */
	protected $salutations;

	/**
	 * @var json
	 * @ORM\Column(type="json")
	 */
	protected $multitranslationOptions;

	/**
	 * @var json
	 * @ORM\Column(type="json")
	 */
	protected $genderNumberOptions;

	/**
	 * @var json
	 * @ORM\Column(type="json")
	 */
	protected $ppcPatterns;

}