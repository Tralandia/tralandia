<?php

namespace Entity\Phrase;

use Doctrine\ORM\Mapping as ORM;
use	Extras\Annotation as EA;

/**
 * @ORM\Entity()
 * @ORM\Table(name="phrase_type",
 * 		indexes={
 * 			@ORM\Index(name="entityName", columns={"entityName"}),
 * 			@ORM\Index(name="entityAttribute", columns={"entityAttribute"}),
 * 			@ORM\Index(name="pluralVariationsRequired", columns={"pluralVariationsRequired"}),
 * 			@ORM\Index(name="genderVariationsRequired", columns={"genderVariationsRequired"}),
 * 			@ORM\Index(name="locativesRequired", columns={"locativesRequired"}),
 * 			@ORM\Index(name="positionRequired", columns={"positionRequired"}),
 * 			@ORM\Index(name="translated", columns={"translated"})
 * 		})
 *
 */
class Type extends \Entity\BaseEntity {

	const TRANSLATE_TO_SUPPORTED = 'supported';
	const TRANSLATE_TO_NONE = 'none';

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
	 * @var boolean
	 * @ORM\Column(type="boolean")
	 */
	protected $preFillForTranslator = FALSE;

	public function isSimple()
	{
		return !($this->pluralVariationsRequired || $this->genderVariationsRequired || $this->locativesRequired || $this->positionRequired);
	}

	public function isHtml()
	{
		return (bool) $this->html;
	}

	public function getVariationsCount(\Entity\Language $language) {
		$count = 1;
		if ($this->pluralVariationsRequired == TRUE) {
			$count = $count * count($language->getPluralsNames());
		}

		if ($this->genderVariationsRequired == TRUE) {
			$count = $count * count($language->getGendersNames());
		}

		if ($this->locativesRequired == TRUE) {
			$count = $count * count($language->getCasesNames());
		}

		return $count;
	}


	/**
	 * @return array
	 */
	public function getEntityParsedName()
	{
		$name = $this->entityName;
		$name = str_replace('\Entity\\', '', $name);
		$name = explode('\\', $name);
		$name = array_unique($name);
		return $name;
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
	 * @return boolean
	 */
	public function getPreFillForTranslator()
	{
		return $this->preFillForTranslator;
	}


	/**
	 * @param boolean $preFillForTranslator
	 */
	public function setPreFillForTranslator($preFillForTranslator)
	{
		$this->preFillForTranslator = $preFillForTranslator;
	}
}
