<?php

namespace Entities\Seo;

use Entities\Dictionary;
use Entities\Location;
use Doctrine\Common\Collections\Collection
use Doctrine\Common\Collections\ArrayCollection


/**
 * @Entity()
 * @Table(name="seo_titlesuffix")
 */
class TitleSuffix extends \BaseEntity {

	/**
	 * @var Collection
	 * @ManyToMany(targetEntity="Location\Location")
	 */
	protected $country;

	/**
	 * @var Collection
	 * @ManyToMany(targetEntity="Dictionary\Language")
	 */
	protected $language;

	/**
	 * @var string
	 * @Column(type="string", nullable=true)
	 */
	protected $suffix;


	public function __construct() {

	}


	/**
	 * @param Location\Location $country
	 * @return TitleSuffix
	 */
	public function setCountry(Location\Location  $country) {
		$this->country = $country;
		return $this;
	}


	/**
	 * @return Location\Location
	 */
	public function getCountry() {
		return $this->country;
	}


	/**
	 * @param Dictionary\Language $language
	 * @return TitleSuffix
	 */
	public function setLanguage(Dictionary\Language  $language) {
		$this->language = $language;
		return $this;
	}


	/**
	 * @return Dictionary\Language
	 */
	public function getLanguage() {
		return $this->language;
	}


	/**
	 * @param string $suffix
	 * @return TitleSuffix
	 */
	public function setSuffix($suffix) {
		$this->suffix = $suffix;
		return $this;
	}


	/**
	 * @return string
	 */
	public function getSuffix() {
		return $this->suffix;
	}

}
