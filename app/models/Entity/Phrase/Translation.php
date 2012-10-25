<?php

namespace Entity\Phrase;

use Doctrine\ORM\Mapping as ORM;
use	Extras\Annotation as EA;


/**
 * @ORM\Entity(repositoryClass="Repository\Phrase\TranslationRepository")
 * @ORM\Table(name="phrase_translation", indexes={@ORM\index(name="timeTranslated", columns={"timeTranslated"}), @ORM\index(name="checked", columns={"checked"})})
 * @EA\Primary(key="id", value="")
 */
class Translation extends \Entity\BaseEntity {

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Phrase", inversedBy="translations", cascade={"persist", "remove"})
	 */
	protected $phrase;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="\Entity\Language", cascade={"persist"})
	 */
	protected $language;

	/**
	 * @var json
	 * @ORM\Column(type="json")
	 */
	protected $variations;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $gender;

	/**
	 * @var integer
	 * @ORM\Column(type="integer")
	 */
	protected $position = 0;

	/**
	 * @var datetime
	 * @ORM\Column(type="datetime", nullable=true)
	 */
	protected $timeTranslated;

	/**
	 * @var boolean
	 * @ORM\Column(type="boolean", nullable=true)
	 */
	protected $checked;

	public function __toString() {
		return $this->variations['translation'];
	}

	//@entity-generator-code --- NEMAZAT !!!

	/* ----------------------------- Methods ----------------------------- */		
	public function __construct()
	{
		parent::__construct();
	}
		
	/**
	 * @param \Entity\Phrase\Phrase
	 * @return \Entity\Phrase\Translation
	 */
	public function setPhrase(\Entity\Phrase\Phrase $phrase)
	{
		$this->phrase = $phrase;

		return $this;
	}
		
	/**
	 * @return \Entity\Phrase\Translation
	 */
	public function unsetPhrase()
	{
		$this->phrase = NULL;

		return $this;
	}
		
	/**
	 * @return \Entity\Phrase\Phrase|NULL
	 */
	public function getPhrase()
	{
		return $this->phrase;
	}
		
	/**
	 * @param \Entity\Language
	 * @return \Entity\Phrase\Translation
	 */
	public function setLanguage(\Entity\Language $language)
	{
		$this->language = $language;

		return $this;
	}
		
	/**
	 * @return \Entity\Phrase\Translation
	 */
	public function unsetLanguage()
	{
		$this->language = NULL;

		return $this;
	}
		
	/**
	 * @return \Entity\Language|NULL
	 */
	public function getLanguage()
	{
		return $this->language;
	}
		
	/**
	 * @param string
	 * @return \Entity\Phrase\Translation
	 */
	public function setTranslation($translation)
	{
		$this->translation = $translation;

		return $this;
	}
		
	/**
	 * @return \Entity\Phrase\Translation
	 */
	public function unsetTranslation()
	{
		$this->translation = NULL;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getTranslation()
	{
		return $this->translation;
	}
		
	/**
	 * @param json
	 * @return \Entity\Phrase\Translation
	 */
	public function setVariations($variations)
	{
		$this->variations = $variations;

		return $this;
	}
		
	/**
	 * @return json|NULL
	 */
	public function getVariations()
	{
		return $this->variations;
	}
		
	/**
	 * @param string
	 * @return \Entity\Phrase\Translation
	 */
	public function setGender($gender)
	{
		$this->gender = $gender;

		return $this;
	}
		
	/**
	 * @return \Entity\Phrase\Translation
	 */
	public function unsetGender()
	{
		$this->gender = NULL;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getGender()
	{
		return $this->gender;
	}
		
	/**
	 * @param integer
	 * @return \Entity\Phrase\Translation
	 */
	public function setPosition($position)
	{
		$this->position = $position;

		return $this;
	}
		
	/**
	 * @return integer|NULL
	 */
	public function getPosition()
	{
		return $this->position;
	}
		
	/**
	 * @param \DateTime
	 * @return \Entity\Phrase\Translation
	 */
	public function setTimeTranslated(\DateTime $timeTranslated)
	{
		$this->timeTranslated = $timeTranslated;

		return $this;
	}
		
	/**
	 * @return \Entity\Phrase\Translation
	 */
	public function unsetTimeTranslated()
	{
		$this->timeTranslated = NULL;

		return $this;
	}
		
	/**
	 * @return \DateTime|NULL
	 */
	public function getTimeTranslated()
	{
		return $this->timeTranslated;
	}
		
	/**
	 * @param boolean
	 * @return \Entity\Phrase\Translation
	 */
	public function setChecked($checked)
	{
		$this->checked = $checked;

		return $this;
	}
		
	/**
	 * @return \Entity\Phrase\Translation
	 */
	public function unsetChecked()
	{
		$this->checked = NULL;

		return $this;
	}
		
	/**
	 * @return boolean|NULL
	 */
	public function getChecked()
	{
		return $this->checked;
	}
}