<?php

namespace Entity\Seo;

use Entity\Phrase;
use Entity\Location;
use Doctrine\ORM\Mapping as ORM;
use	Extras\Annotation as EA;

/**
 * @ORM\Entity()
 * @ORM\Table(name="seo_titlesuffix")
 *
 */
class TitleSuffix extends \Entity\BaseEntity {

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entity\Location\Location")
	 */
	protected $country;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entity\Language")
	 */
	protected $language;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $suffix;

			//@entity-generator-code --- NEMAZAT !!!

	/* ----------------------------- Methods ----------------------------- */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * @param \Entity\Location\Location
	 * @return \Entity\Seo\TitleSuffix
	 */
	public function setCountry(\Entity\Location\Location $country)
	{
		$this->country = $country;

		return $this;
	}

	/**
	 * @return \Entity\Seo\TitleSuffix
	 */
	public function unsetCountry()
	{
		$this->country = NULL;

		return $this;
	}

	/**
	 * @return \Entity\Location\Location|NULL
	 */
	public function getCountry()
	{
		return $this->country;
	}

	/**
	 * @param \Entity\Language
	 * @return \Entity\Seo\TitleSuffix
	 */
	public function setLanguage(\Entity\Language $language)
	{
		$this->language = $language;

		return $this;
	}

	/**
	 * @return \Entity\Seo\TitleSuffix
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
	 * @return \Entity\Seo\TitleSuffix
	 */
	public function setSuffix($suffix)
	{
		$this->suffix = $suffix;

		return $this;
	}

	/**
	 * @return \Entity\Seo\TitleSuffix
	 */
	public function unsetSuffix()
	{
		$this->suffix = NULL;

		return $this;
	}

	/**
	 * @return string|NULL
	 */
	public function getSuffix()
	{
		return $this->suffix;
	}
}
