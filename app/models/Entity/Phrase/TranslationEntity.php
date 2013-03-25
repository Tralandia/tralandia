<?php

namespace Entity\Phrase;

use Doctrine\ORM\Mapping as ORM;
use	Extras\Annotation as EA;
use Nette\Utils\Arrays;

/**
 * @ORM\Entity(repositoryClass="Repository\Phrase\TranslationRepository")
 * @ORM\Table(name="phrase_translation",
 * 				indexes={
 * 					@ORM\index(name="timeTranslated", columns={"timeTranslated"}),
 * 					@ORM\index(name="translation", columns={"translation"}),
 * 					@ORM\index(name="checked", columns={"checked"})
 * 				}
 * 			)
 * @EA\Primary(key="id", value="")
 * @EA\Generator(skip="{setTranslation, getTranslation, setVariations, updateVariations}")
 */
class Translation extends \Entity\BaseEntity {

	const AFTER = 'After';
	const BEFORE = 'Before';

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
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $translation;

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
	 * @var string
	 * @ORM\Column(type="string")
	 */
	protected $position = self::BEFORE;

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
		if($this->phrase->getType()->isSimple()) {
			$translation = $this->translation;
		} else {
			$translation = $this->getDefaultVariation();
		}
		return strlen($translation) ? $translation : '{!!' . $this->id . '}';
	}

	/**
	 * @param string
	 * @return \Entity\Phrase\Translation
	 */
	public function setTranslation($translation)
	{
		$this->translation = $translation;

		list($plural, $gender, $case) = $this->getDefaultVariationPath();
		$this->variations[$plural][$gender][$case] = $translation;

		return $this;
	}

	/**
	 * @return string|NULL
	 */
	public function getTranslation()
	{
		return $this->getDefaultVariation();
	}

	/**
	 * @param array
	 * @return \Entity\Phrase\Translation
	 */
	public function setVariations(array $variations)
	{
		return $this->_updateVariations('set', $variations);
	}

	public function updateVariations(array $variations) {
		return $this->_updateVariations('update', $variations);
	}

	protected function _updateVariations($option, array $variations) {

		if($option == 'set') {
			$foreach = $this->phrase->getTranslationVariationsMatrix($this->language);
			$isset = $variations;
			$variationsTemp = array();
		} else {
			$foreach = $variations;
			$isset = $this->variations;
			$variationsTemp = $this->variations;
		}

		if(isset($this->variations)) {
			foreach ($foreach as $pluralKey => $genders) {
				foreach ($genders as $genderKey => $cases) {
					foreach ($cases as $caseKey => $caseValue) {
						try {
							$i = Arrays::get($isset, array($pluralKey, $genderKey, $caseKey));
							$variationsTemp[$pluralKey][$genderKey][$caseKey] = $variations[$pluralKey][$genderKey][$caseKey];
						} catch (\Nette\InvalidArgumentException $e) {
							$this->wrongVariationsScheme($this->variations, $variations);
						}
					}
				}
			}

			$this->variations = $variationsTemp;
		} else {
			$this->variations = $variations;
		}

		$this->translation = $this->getDefaultVariation();

		return $this;
	}

	protected function wrongVariationsScheme($expect, $recieved) {
		throw new \Nette\InvalidArgumentException('Argument "$variations" does not match with the expected value');
	}

	public function getDefaultVariation() {
		list($defaultPlural, $defaultGender, $defaultCase) = $this->getDefaultVariationPath();
		return $this->variations[$defaultPlural][$defaultGender][$defaultCase];
	}

	public function getVariation($plural = NULL, $gender = NULL, $case = NULL) {
		list($defaultPlural, $defaultGender, $defaultCase) = $this->getDefaultVariationPath();
		$variation = Arrays::get($this->variations, [$plural === NULL ? $defaultPlural : $plural, $gender === NULL ? $defaultGender : $gender, $case === NULL ? $defaultCase : $case], NULL);
		//$variation = $this->variations[$plural === NULL ? $defaultPlural : $plural][$gender === NULL ? $defaultGender : $gender][$case === NULL ? $defaultCase : $case];
		return $variation;
	}

	public function getDefaultVariationPath() {
		$return = array(\Entity\Language::DEFAULT_SINGULAR, \Entity\Language::DEFAULT_GENDER, \Entity\Language::DEFAULT_CASE);

		return $return;
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
	 * @return \Entity\Phrase\Translation
	 */
	public function unsetTranslation()
	{
		$this->translation = NULL;

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
	 * @param string
	 * @return \Entity\Phrase\Translation
	 */
	public function setPosition($position)
	{
		$this->position = $position;

		return $this;
	}
		
	/**
	 * @return string|NULL
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