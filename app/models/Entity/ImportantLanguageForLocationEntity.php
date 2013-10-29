<?php

namespace Entity;

use Entity\Phrase;
use Doctrine\ORM\Mapping as ORM;
use	Extras\Annotation as EA;

/**
 * @ORM\Entity
 * @ORM\Table(name="important_language_for_location")
*/
class ImportantLanguageForLocation extends \Entity\BaseEntity {

	/**
	 * @var \Entity\Location\Location
	 * @ORM\ManyToOne(targetEntity="\Entity\Location\Location", inversedBy="importantLanguagesForLocation")
	 * @ORM\JoinColumn(onDelete="CASCADE")
	 */
	protected $location;


	/**
	 * @var \Entity\Language
	 * @ORM\ManyToOne(targetEntity="\Entity\Language")
	 */
	protected $language;


	/**
	 * @var string
	 * @ORM\Column(type="string")
	 */
	protected $keyword;


	/**
	 * @var string
	 * @ORM\Column(type="string")
	 */
	protected $keywordCombined;


	/**
	 * @var integer
	 * @ORM\Column(type="integer")
	 */
	protected $dailyPhraseSearches;


	//@entity-generator-code --- NEMAZAT !!!

	/* ----------------------------- Methods ----------------------------- */
	public function __construct()
	{
		parent::__construct();
	}


	/**
	 * @return \Entity\Location\Location
	 */
	public function getLocation()
	{
		return $this->location;
	}


	/**
	 * @param \Entity\Location\Location $location
	 */
	public function setLocation($location)
	{
		$this->location = $location;
	}


	/**
	 * @return \Entity\Language
	 */
	public function getLanguage()
	{
		return $this->language;
	}


	/**
	 * @param \Entity\Language $language
	 */
	public function setLanguage($language)
	{
		$this->language = $language;
	}


	/**
	 * @return string
	 */
	public function getKeyword()
	{
		return $this->keyword;
	}


	/**
	 * @param string $keyword
	 */
	public function setKeyword($keyword)
	{
		$this->keyword = $keyword;
	}


	/**
	 * @return string
	 */
	public function getKeywordCombined()
	{
		return $this->keywordCombined;
	}


	/**
	 * @param string $keywordCombined
	 */
	public function setKeywordCombined($keywordCombined)
	{
		$this->keywordCombined = $keywordCombined;
	}


	/**
	 * @return int
	 */
	public function getDailyPhraseSearches()
	{
		return $this->dailyPhraseSearches;
	}


	/**
	 * @param int $dailyPhraseSearches
	 */
	public function setDailyPhraseSearches($dailyPhraseSearches)
	{
		$this->dailyPhraseSearches = $dailyPhraseSearches;
	}
}
