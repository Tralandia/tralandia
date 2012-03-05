<?php

namespace Entities\Location;

use Entities\Dictionary;
use Entities\Location;
use Doctrine\Common\Collections\Collection
use Doctrine\Common\Collections\ArrayCollection


/**
 * @Entity()
 * @Table(name="location_location")
 */
class Location extends \BaseEntityDetails {

	/**
	 * @var Collection
	 * @ManyToMany(targetEntity="Dictionary\Language")
	 */
	protected $languages;

	/**
	 * @var Collection
	 * @ManyToMany(targetEntity="Dictionary\Phrase")
	 */
	protected $name;

	/**
	 * @var Collection
	 * @ManyToMany(targetEntity="Dictionary\Phrase")
	 */
	protected $nameOfficial;

	/**
	 * @var Collection
	 * @ManyToMany(targetEntity="Dictionary\Phrase")
	 */
	protected $nameShort;

	/**
	 * @var string
	 * @Column(type="string", nullable=true)
	 */
	protected $iso;

	/**
	 * @var slug
	 * @Column(type="slug")
	 */
	protected $slug;

	/**
	 * @var integer
	 * @Column(type="integer")
	 */
	protected $nestedLeft;

	/**
	 * @var integer
	 * @Column(type="integer")
	 */
	protected $nestedRight;

	/**
	 * @var Collection
	 * @Column(type="Type")
	 */
	protected $type;

	/**
	 * @var json
	 * @Column(type="json")
	 */
	protected $polygon;

	/**
	 * @var latlong
	 * @Column(type="latlong")
	 */
	protected $latitude;

	/**
	 * @var latlong
	 * @Column(type="latlong")
	 */
	protected $longitude;

	/**
	 * @var integer
	 * @Column(type="integer")
	 */
	protected $defaultZoom;

	/**
	 * @var Collection
	 * @Column(type="Travelink")
	 */
	protected $travelingsFrom;

	/**
	 * @var Collection
	 * @Column(type="Travelink")
	 */
	protected $travelingsTo;

	/**
	 * @var Collection
	 * @Column(type="Country")
	 */
	protected $country;


	public function __construct() {

	}


	/**
	 * @param Dictionary\Language $languages
	 * @return Location
	 */
	public function setLanguages(Dictionary\Language  $languages) {
		$this->languages = $languages;
		return $this;
	}


	/**
	 * @return Dictionary\Language
	 */
	public function getLanguages() {
		return $this->languages;
	}


	/**
	 * @param Dictionary\Phrase $name
	 * @return Location
	 */
	public function setName(Dictionary\Phrase  $name) {
		$this->name = $name;
		return $this;
	}


	/**
	 * @return Dictionary\Phrase
	 */
	public function getName() {
		return $this->name;
	}


	/**
	 * @param Dictionary\Phrase $nameOfficial
	 * @return Location
	 */
	public function setNameOfficial(Dictionary\Phrase  $nameOfficial) {
		$this->nameOfficial = $nameOfficial;
		return $this;
	}


	/**
	 * @return Dictionary\Phrase
	 */
	public function getNameOfficial() {
		return $this->nameOfficial;
	}


	/**
	 * @param Dictionary\Phrase $nameShort
	 * @return Location
	 */
	public function setNameShort(Dictionary\Phrase  $nameShort) {
		$this->nameShort = $nameShort;
		return $this;
	}


	/**
	 * @return Dictionary\Phrase
	 */
	public function getNameShort() {
		return $this->nameShort;
	}


	/**
	 * @param string $iso
	 * @return Location
	 */
	public function setIso($iso) {
		$this->iso = $iso;
		return $this;
	}


	/**
	 * @return string
	 */
	public function getIso() {
		return $this->iso;
	}


	/**
	 * @param slug $slug
	 * @return Location
	 */
	public function setSlug($slug) {
		$this->slug = $slug;
		return $this;
	}


	/**
	 * @return slug
	 */
	public function getSlug() {
		return $this->slug;
	}


	/**
	 * @param integer $nestedLeft
	 * @return Location
	 */
	public function setNestedLeft($nestedLeft) {
		$this->nestedLeft = $nestedLeft;
		return $this;
	}


	/**
	 * @return integer
	 */
	public function getNestedLeft() {
		return $this->nestedLeft;
	}


	/**
	 * @param integer $nestedRight
	 * @return Location
	 */
	public function setNestedRight($nestedRight) {
		$this->nestedRight = $nestedRight;
		return $this;
	}


	/**
	 * @return integer
	 */
	public function getNestedRight() {
		return $this->nestedRight;
	}


	/**
	 * @param Type $type
	 * @return Location
	 */
	public function setType(Type  $type) {
		$this->type = $type;
		return $this;
	}


	/**
	 * @return Type
	 */
	public function getType() {
		return $this->type;
	}


	/**
	 * @param json $polygon
	 * @return Location
	 */
	public function setPolygon($polygon) {
		$this->polygon = $polygon;
		return $this;
	}


	/**
	 * @return json
	 */
	public function getPolygon() {
		return $this->polygon;
	}


	/**
	 * @param latlong $latitude
	 * @return Location
	 */
	public function setLatitude($latitude) {
		$this->latitude = $latitude;
		return $this;
	}


	/**
	 * @return latlong
	 */
	public function getLatitude() {
		return $this->latitude;
	}


	/**
	 * @param latlong $longitude
	 * @return Location
	 */
	public function setLongitude($longitude) {
		$this->longitude = $longitude;
		return $this;
	}


	/**
	 * @return latlong
	 */
	public function getLongitude() {
		return $this->longitude;
	}


	/**
	 * @param integer $defaultZoom
	 * @return Location
	 */
	public function setDefaultZoom($defaultZoom) {
		$this->defaultZoom = $defaultZoom;
		return $this;
	}


	/**
	 * @return integer
	 */
	public function getDefaultZoom() {
		return $this->defaultZoom;
	}


	/**
	 * @param Travelink $travelingsFrom
	 * @return Location
	 */
	public function setTravelingsFrom(Travelink  $travelingsFrom) {
		$this->travelingsFrom = $travelingsFrom;
		return $this;
	}


	/**
	 * @return Travelink
	 */
	public function getTravelingsFrom() {
		return $this->travelingsFrom;
	}


	/**
	 * @param Travelink $travelingsTo
	 * @return Location
	 */
	public function setTravelingsTo(Travelink  $travelingsTo) {
		$this->travelingsTo = $travelingsTo;
		return $this;
	}


	/**
	 * @return Travelink
	 */
	public function getTravelingsTo() {
		return $this->travelingsTo;
	}


	/**
	 * @param Country $country
	 * @return Location
	 */
	public function setCountry(Country  $country) {
		$this->country = $country;
		return $this;
	}


	/**
	 * @return Country
	 */
	public function getCountry() {
		return $this->country;
	}

}
