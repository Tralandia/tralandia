<?php

namespace Seo;

use Attraction;
use Dictionary;
use Location;
use Medium;
use Rental;
use Doctrine\Common\Collections\Collection
use Doctrine\Common\Collections\ArrayCollection


/**
 * @Entity()
 * @Table(name="seo_seourl")
 */
class SeoUrl extends \BaseEntity {

	/**
	 * @var Collection
	 * @ManyToMany(targetEntity="Location\Location")
	 */
	protected $country;

	/**
	 * @var Collection
	 * @ManyToMany(targetEntity="Rental\Type")
	 */
	protected $rentalType;

	/**
	 * @var Collection
	 * @ManyToMany(targetEntity="Location\Location")
	 */
	protected $location;

	/**
	 * @var integer
	 * @Column(type="integer")
	 */
	protected $page;

	/**
	 * @var Collection
	 * @ManyToMany(targetEntity="Rental\Amenity\Amenity")
	 */
	protected $tag;

	/**
	 * @var Collection
	 * @ManyToMany(targetEntity="Attraction\Attraction")
	 */
	protected $attractionType;

	/**
	 * @var Collection
	 * @ManyToMany(targetEntity="Medium\Medium")
	 */
	protected $media;

	/**
	 * @var Collection
	 * @ManyToMany(targetEntity="Dictionary\Phrase")
	 */
	protected $title;

	/**
	 * @var Collection
	 * @ManyToMany(targetEntity="Dictionary\Phrase")
	 */
	protected $h1;

	/**
	 * @var Collection
	 * @ManyToMany(targetEntity="Dictionary\Phrase")
	 */
	protected $tabName;

	/**
	 * @var Collection
	 * @ManyToMany(targetEntity="Dictionary\Phrase")
	 */
	protected $description;

	/**
	 * @var Collection
	 * @ManyToMany(targetEntity="Dictionary\Phrase")
	 */
	protected $ppcKeywords;


	public function __construct() {

	}


	/**
	 * @param Location\Location $country
	 * @return SeoUrl
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
	 * @param Rental\Type $rentalType
	 * @return SeoUrl
	 */
	public function setRentalType(Rental\Type  $rentalType) {
		$this->rentalType = $rentalType;
		return $this;
	}


	/**
	 * @return Rental\Type
	 */
	public function getRentalType() {
		return $this->rentalType;
	}


	/**
	 * @param Location\Location $location
	 * @return SeoUrl
	 */
	public function setLocation(Location\Location  $location) {
		$this->location = $location;
		return $this;
	}


	/**
	 * @return Location\Location
	 */
	public function getLocation() {
		return $this->location;
	}


	/**
	 * @param integer $page
	 * @return SeoUrl
	 */
	public function setPage($page) {
		$this->page = $page;
		return $this;
	}


	/**
	 * @return integer
	 */
	public function getPage() {
		return $this->page;
	}


	/**
	 * @param Rental\Amenity\Amenity $tag
	 * @return SeoUrl
	 */
	public function setTag(Rental\Amenity\Amenity  $tag) {
		$this->tag = $tag;
		return $this;
	}


	/**
	 * @return Rental\Amenity\Amenity
	 */
	public function getTag() {
		return $this->tag;
	}


	/**
	 * @param Attraction\Attraction $attractionType
	 * @return SeoUrl
	 */
	public function setAttractionType(Attraction\Attraction  $attractionType) {
		$this->attractionType = $attractionType;
		return $this;
	}


	/**
	 * @return Attraction\Attraction
	 */
	public function getAttractionType() {
		return $this->attractionType;
	}


	/**
	 * @param Medium\Medium $media
	 * @return SeoUrl
	 */
	public function setMedia(Medium\Medium  $media) {
		$this->media = $media;
		return $this;
	}


	/**
	 * @return Medium\Medium
	 */
	public function getMedia() {
		return $this->media;
	}


	/**
	 * @param Dictionary\Phrase $title
	 * @return SeoUrl
	 */
	public function setTitle(Dictionary\Phrase  $title) {
		$this->title = $title;
		return $this;
	}


	/**
	 * @return Dictionary\Phrase
	 */
	public function getTitle() {
		return $this->title;
	}


	/**
	 * @param Dictionary\Phrase $h1
	 * @return SeoUrl
	 */
	public function setH1(Dictionary\Phrase  $h1) {
		$this->h1 = $h1;
		return $this;
	}


	/**
	 * @return Dictionary\Phrase
	 */
	public function getH1() {
		return $this->h1;
	}


	/**
	 * @param Dictionary\Phrase $tabName
	 * @return SeoUrl
	 */
	public function setTabName(Dictionary\Phrase  $tabName) {
		$this->tabName = $tabName;
		return $this;
	}


	/**
	 * @return Dictionary\Phrase
	 */
	public function getTabName() {
		return $this->tabName;
	}


	/**
	 * @param Dictionary\Phrase $description
	 * @return SeoUrl
	 */
	public function setDescription(Dictionary\Phrase  $description) {
		$this->description = $description;
		return $this;
	}


	/**
	 * @return Dictionary\Phrase
	 */
	public function getDescription() {
		return $this->description;
	}


	/**
	 * @param Dictionary\Phrase $ppcKeywords
	 * @return SeoUrl
	 */
	public function setPpcKeywords(Dictionary\Phrase  $ppcKeywords) {
		$this->ppcKeywords = $ppcKeywords;
		return $this;
	}


	/**
	 * @return Dictionary\Phrase
	 */
	public function getPpcKeywords() {
		return $this->ppcKeywords;
	}

}
