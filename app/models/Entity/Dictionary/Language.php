<?php

namespace Entity\Dictionary;

use Entity\Dictionary;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="dictionary_language")
 */
class Language extends \Entity\BaseEntityDetails {

	const SUPPORTED = TRUE;
	const NOT_SUPPORTED = FALSE;

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Phrase", cascade={"persist", "remove"})
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
	 * @ORM\Column(type="json", nullable=true)
	 */
	protected $salutations;

	/**
	 * @var json
	 * @ORM\Column(type="json", nullable=true)
	 */
	protected $multitranslationOptions;

	/**
	 * @var json
	 * @ORM\Column(type="json", nullable=true)
	 */
	protected $genderNumberOptions;

	/**
	 * @var json
	 * @ORM\Column(type="json", nullable=true)
	 */
	protected $ppcPatterns;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Entity\Location\Country", inversedBy="languages")
	 */
	protected $countries;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Entity\Rental\Rental", inversedBy="languagesSpoken")
	 */
	protected $rentals;

	//@entity-generator-code

}