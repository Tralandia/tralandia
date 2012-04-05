<?php

namespace Entity\Dictionary;

use Entity\Dictionary;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="dictionary_type")
 */
class Type extends \Entity\BaseEntity {

	const TRANSLATION_LEVEL_PASSIVE = 0;
	const TRANSLATION_LEVEL_ACTIVE = 2;
	const TRANSLATION_LEVEL_NATIVE = 4;
	const TRANSLATION_LEVEL_MARKETING = 6;

	const REQUIRED_LANGUAGES_SUPPORTED = 'supportedLanguages';
	const REQUIRED_LANGUAGES_INCOMING = 'incomingLanguages';

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
	 * @ORM\Column(type="string")
	 * "supportedLanguages", "incomingLanguages" or list of IDs separated by ",": ",1,2,3,4,"
	 */
	protected $requiredLanguages;
	
	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $entityAttribute;

	/**
	 * @var integer
	 * @ORM\Column(type="integer")
	 */
	protected $translationLevelRequirement = FALSE;

	/**
	 * @var boolean
	 * @ORM\Column(type="boolean")
	 */
	protected $multitranslationRequired = FALSE;

	/**
	 * @var boolean
	 * @ORM\Column(type="boolean")
	 */
	protected $genderNumberRequired = FALSE;

	/**
	 * @var boolean
	 * @ORM\Column(type="boolean")
	 */
	protected $locativeRequired = FALSE;

	/**
	 * @var boolean
	 * @ORM\Column(type="boolean")
	 */
	protected $positionRequired = FALSE;

	/**
	 * @var boolean
	 * @ORM\Column(type="boolean")
	 */
	protected $webalizedRequired = FALSE;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $helpForTranslator;

	/**
	 * @var boolean
	 * @ORM\Column(type="boolean", nullable=true)
	 */
	protected $checkingRequired;
	

	//@entity-generator-code

}