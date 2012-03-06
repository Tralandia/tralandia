<?php

namespace Entities\Seo;

use Entities\Dictionary;
use Entities\Location;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="seo_titlesuffix")
 */
class TitleSuffix extends \BaseEntity {

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Location\Location")
	 */
	protected $country;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Dictionary\Language")
	 */
	protected $language;

	/**
	 * @var string
	 * @ORM\ManyToMany(type="string", nullable=true)
	 */
	protected $suffix;


	public function __construct() {
		parent::__construct();

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
