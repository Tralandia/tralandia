<?php

namespace Entity\Phrase;

use Doctrine\ORM\Mapping as ORM;
use	Extras\Annotation as EA;

/**
 * @ORM\Entity()
 * @ORM\Table(name="phrase_type", indexes={@ORM\index(name="entityName", columns={"entityName"}), @ORM\index(name="entityAttribute", columns={"entityAttribute"}), @ORM\index(name="pluralVariationsRequired", columns={"pluralVariationsRequired"}), @ORM\index(name="genderVariationsRequired", columns={"genderVariationsRequired"}), @ORM\index(name="locativesRequired", columns={"locativesRequired"}), @ORM\index(name="positionRequired", columns={"positionRequired"}), @ORM\index(name="checkingRequired", columns={"checkingRequired"})})
 * @EA\Primary(key="id", value="name")
 */
class Type extends \Entity\BaseEntity {

	const TRANSLATE_TO_SUPPORTED = 'supported';
	const TRANSLATE_TO_NONE = 'none';

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $name;

	/**
	 * @var string
	 * @ORM\Column(type="string")
	 */
	protected $translateTo = self::TRANSLATE_TO_SUPPORTED;

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
	 * @var boolean
	 * @ORM\Column(type="boolean")
	 */
	protected $pluralVariationsRequired = FALSE;

	/**
	 * @var boolean
	 * @ORM\Column(type="boolean")
	 * whether we need to know the gender of this word / translation, for example "chata" is feminine
	 */
	protected $genderRequired = FALSE;

	/**
	 * @var boolean
	 * @ORM\Column(type="boolean")
	 * whether we need the variations of expression based on gender, for example "lacny" could be "lacne" etc.
	 */
	protected $genderVariationsRequired = FALSE;

	/**
	 * @var boolean
	 * @ORM\Column(type="boolean")
	 */
	protected $locativesRequired = FALSE;

	/**
	 * @var boolean
	 * @ORM\Column(type="boolean")
	 */
	protected $positionRequired = FALSE;

	/**
	 * @var boolean
	 * @ORM\Column(type="boolean", nullable=true)
	 */
	protected $checkingRequired;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $helpForTranslator;

	/**
	 * @var boolean
	 * @ORM\Column(type="boolean")
	 */
	protected $html = FALSE;

	/**
	 * @var boolean
	 * @ORM\Column(type="boolean")
	 */
	protected $translated = FALSE;

	/**
	 * @var integer
	 * @ORM\Column(type="integer")
	 * in EUR
	 */
	protected $monthlyBudget = 0;


	public function isSimple()
	{
		return !($this->pluralVariationsRequired || $this->genderVariationsRequired || $this->locativesRequired || $this->positionRequired);
	}

	public function isHtml()
	{
		return $this->html;
	}

	//@entity-generator-code --- NEMAZAT !!!

	/* ----------------------------- Methods ----------------------------- */		
	public function __construct()
	{
		parent::__construct();
	}
		
	/**
	 * @param string
	 * @return \Entity\Phrase\Type
	 */
	public function setName($name)
	{
		$this->name = $name;

		return $this;
	}
		
	/**
	 * @return \Entity\Phrase\Type
	 */
	public function unsetName()
	{
		$this->name = NULL;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getName()
	{
		return $this->name;
	}
		
	/**
	 * @param string
	 * @return \Entity\Phrase\Type
	 */
	public function setTranslateTo($translateTo)
	{
		$this->translateTo = $translateTo;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getTranslateTo()
	{
		return $this->translateTo;
	}
		
	/**
	 * @param string
	 * @return \Entity\Phrase\Type
	 */
	public function setEntityName($entityName)
	{
		$this->entityName = $entityName;

		return $this;
	}
		
	/**
	 * @return \Entity\Phrase\Type
	 */
	public function unsetEntityName()
	{
		$this->entityName = NULL;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getEntityName()
	{
		return $this->entityName;
	}
		
	/**
	 * @param string
	 * @return \Entity\Phrase\Type
	 */
	public function setEntityAttribute($entityAttribute)
	{
		$this->entityAttribute = $entityAttribute;

		return $this;
	}
		
	/**
	 * @return \Entity\Phrase\Type
	 */
	public function unsetEntityAttribute()
	{
		$this->entityAttribute = NULL;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getEntityAttribute()
	{
		return $this->entityAttribute;
	}
		
	/**
	 * @param boolean
	 * @return \Entity\Phrase\Type
	 */
	public function setPluralVariationsRequired($pluralVariationsRequired)
	{
		$this->pluralVariationsRequired = $pluralVariationsRequired;

		return $this;
	}
		
	/**
	 * @return boolean|NULL
	 */
	public function getPluralVariationsRequired()
	{
		return $this->pluralVariationsRequired;
	}
		
	/**
	 * @param boolean
	 * @return \Entity\Phrase\Type
	 */
	public function setGenderRequired($genderRequired)
	{
		$this->genderRequired = $genderRequired;

		return $this;
	}
		
	/**
	 * @return boolean|NULL
	 */
	public function getGenderRequired()
	{
		return $this->genderRequired;
	}
		
	/**
	 * @param boolean
	 * @return \Entity\Phrase\Type
	 */
	public function setGenderVariationsRequired($genderVariationsRequired)
	{
		$this->genderVariationsRequired = $genderVariationsRequired;

		return $this;
	}
		
	/**
	 * @return boolean|NULL
	 */
	public function getGenderVariationsRequired()
	{
		return $this->genderVariationsRequired;
	}
		
	/**
	 * @param boolean
	 * @return \Entity\Phrase\Type
	 */
	public function setLocativesRequired($locativesRequired)
	{
		$this->locativesRequired = $locativesRequired;

		return $this;
	}
		
	/**
	 * @return boolean|NULL
	 */
	public function getLocativesRequired()
	{
		return $this->locativesRequired;
	}
		
	/**
	 * @param boolean
	 * @return \Entity\Phrase\Type
	 */
	public function setPositionRequired($positionRequired)
	{
		$this->positionRequired = $positionRequired;

		return $this;
	}
		
	/**
	 * @return boolean|NULL
	 */
	public function getPositionRequired()
	{
		return $this->positionRequired;
	}
		
	/**
	 * @param boolean
	 * @return \Entity\Phrase\Type
	 */
	public function setCheckingRequired($checkingRequired)
	{
		$this->checkingRequired = $checkingRequired;

		return $this;
	}
		
	/**
	 * @return \Entity\Phrase\Type
	 */
	public function unsetCheckingRequired()
	{
		$this->checkingRequired = NULL;

		return $this;
	}
		
	/**
	 * @return boolean|NULL
	 */
	public function getCheckingRequired()
	{
		return $this->checkingRequired;
	}
		
	/**
	 * @param string
	 * @return \Entity\Phrase\Type
	 */
	public function setHelpForTranslator($helpForTranslator)
	{
		$this->helpForTranslator = $helpForTranslator;

		return $this;
	}
		
	/**
	 * @return \Entity\Phrase\Type
	 */
	public function unsetHelpForTranslator()
	{
		$this->helpForTranslator = NULL;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getHelpForTranslator()
	{
		return $this->helpForTranslator;
	}
		
	/**
	 * @param boolean
	 * @return \Entity\Phrase\Type
	 */
	public function setHtml($html)
	{
		$this->html = $html;

		return $this;
	}
		
	/**
	 * @return boolean|NULL
	 */
	public function getHtml()
	{
		return $this->html;
	}
		
	/**
	 * @param boolean
	 * @return \Entity\Phrase\Type
	 */
	public function setTranslated($translated)
	{
		$this->translated = $translated;

		return $this;
	}
		
	/**
	 * @return boolean|NULL
	 */
	public function getTranslated()
	{
		return $this->translated;
	}
		
	/**
	 * @param integer
	 * @return \Entity\Phrase\Type
	 */
	public function setMonthlyBudget($monthlyBudget)
	{
		$this->monthlyBudget = $monthlyBudget;

		return $this;
	}
		
	/**
	 * @return integer|NULL
	 */
	public function getMonthlyBudget()
	{
		return $this->monthlyBudget;
	}
}