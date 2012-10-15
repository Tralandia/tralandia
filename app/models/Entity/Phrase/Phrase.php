<?php

namespace Entity\Phrase;

use Doctrine\ORM\Mapping as ORM;
use	Extras\Annotation as EA;

/**
 * @ORM\Entity()
 * @ORM\Table(name="phrase", indexes={@ORM\index(name="ready", columns={"ready"})})
 * @EA\Primary(key="id", value="translations")
 */
class Phrase extends \Entity\BaseEntityDetails {

	/**
	 * @var Collection
	 * @ORM\OneToMany(targetEntity="Translation", mappedBy="phrase", cascade={"persist", "remove"})
	 */
	protected $translations;

	/**
	 * @var boolean
	 * @ORM\Column(type="boolean")
	 */
	protected $ready = FALSE; 

	/**
	 * @var boolean
	 * @ORM\Column(type="boolean")
	 */
	protected $corrected = FALSE; 

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Type", cascade={"persist"})
	 */
	protected $type;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="\Entity\Language", cascade={"persist"})
	 * This will be used by Locations (localities) to make sure we know the original language of the name of the locality to translate from
	 */
	protected $sourceLanguage;

	//@entity-generator-code --- NEMAZAT !!!

	/* ----------------------------- Methods ----------------------------- */		
	public function __construct()
	{
		parent::__construct();

		$this->translations = new \Doctrine\Common\Collections\ArrayCollection;
	}
		
	/**
	 * @param \Entity\Phrase\Translation
	 * @return \Entity\Phrase\Phrase
	 */
	public function addTranslation(\Entity\Phrase\Translation $translation)
	{
		if(!$this->translations->contains($translation)) {
			$this->translations->add($translation);
		}
		$translation->setPhrase($this);

		return $this;
	}
		
	/**
	 * @param \Entity\Phrase\Translation
	 * @return \Entity\Phrase\Phrase
	 */
	public function removeTranslation(\Entity\Phrase\Translation $translation)
	{
		if($this->translations->contains($translation)) {
			$this->translations->removeElement($translation);
		}
		$translation->unsetPhrase();

		return $this;
	}
		
	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection of \Entity\Phrase\Translation
	 */
	public function getTranslations()
	{
		return $this->translations;
	}
		
	/**
	 * @param boolean
	 * @return \Entity\Phrase\Phrase
	 */
	public function setReady($ready)
	{
		$this->ready = $ready;

		return $this;
	}
		
	/**
	 * @return boolean|NULL
	 */
	public function getReady()
	{
		return $this->ready;
	}
		
	/**
	 * @param boolean
	 * @return \Entity\Phrase\Phrase
	 */
	public function setCorrected($corrected)
	{
		$this->corrected = $corrected;

		return $this;
	}
		
	/**
	 * @return boolean|NULL
	 */
	public function getCorrected()
	{
		return $this->corrected;
	}
		
	/**
	 * @param \Entity\Phrase\Type
	 * @return \Entity\Phrase\Phrase
	 */
	public function setType(\Entity\Phrase\Type $type)
	{
		$this->type = $type;

		return $this;
	}
		
	/**
	 * @return \Entity\Phrase\Phrase
	 */
	public function unsetType()
	{
		$this->type = NULL;

		return $this;
	}
		
	/**
	 * @return \Entity\Phrase\Type|NULL
	 */
	public function getType()
	{
		return $this->type;
	}
		
	/**
	 * @param \Entity\Language
	 * @return \Entity\Phrase\Phrase
	 */
	public function setSourceLanguage(\Entity\Language $sourceLanguage)
	{
		$this->sourceLanguage = $sourceLanguage;

		return $this;
	}
		
	/**
	 * @return \Entity\Phrase\Phrase
	 */
	public function unsetSourceLanguage()
	{
		$this->sourceLanguage = NULL;

		return $this;
	}
		
	/**
	 * @return \Entity\Language|NULL
	 */
	public function getSourceLanguage()
	{
		return $this->sourceLanguage;
	}
}