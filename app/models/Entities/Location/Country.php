<?php

namespace Entities\Location;

use Entities\Contact;
use Entities\Dictionary;
use Entities\Location;
use Doctrine\Common\Collections\Collection
use Doctrine\Common\Collections\ArrayCollection


/**
 * @Entity()
 * @Table(name="location_country")
 */
class Country extends \BaseEntityDetails {

	/**
	 * @var Collection
	 * @ManyToMany(targetEntity="Currency")
	 */
	protected $defaultCurrency;

	/**
	 * @var Collection
	 * @ManyToMany(targetEntity="Currency")
	 */
	protected $currencies;

	/**
	 * @var Collection
	 * @ManyToMany(targetEntity="Dictionary\Language")
	 */
	protected $defaultLanguage;

	/**
	 * @var integer
	 * @Column(type="integer")
	 */
	protected $population;

	/**
	 * @var string
	 * @Column(type="string", nullable=true)
	 */
	protected $phonePrefix;

	/**
	 * @var Collection
	 * @Column(type="Location")
	 */
	protected $neighbours;

	/**
	 * @var Collection
	 * @ManyToMany(targetEntity="Contact\Contact")
	 */
	protected $fbGroup;

	/**
	 * @var string
	 * @Column(type="string", nullable=true)
	 */
	protected $capitalCity;

	/**
	 * @var Collection
	 * @ManyToMany(targetEntity="Contact\Contact")
	 */
	protected $phoneNumberEmergency;

	/**
	 * @var Collection
	 * @ManyToMany(targetEntity="Contact\Contact")
	 */
	protected $phoneNumberPolice;

	/**
	 * @var Collection
	 * @ManyToMany(targetEntity="Contact\Contact")
	 */
	protected $phoneNumberMedical;

	/**
	 * @var Collection
	 * @ManyToMany(targetEntity="Contact\Contact")
	 */
	protected $phoneNumberFire;

	/**
	 * @var Collection
	 * @ManyToMany(targetEntity="Contact\Contact")
	 */
	protected $wikipediaLink;

	/**
	 * @var string
	 * @Column(type="string", nullable=true)
	 */
	protected $drivingSide;

	/**
	 * @var price
	 * @Column(type="price")
	 */
	protected $pricesPizza;

	/**
	 * @var price
	 * @Column(type="price")
	 */
	protected $pricesDinner;

	/**
	 * @var text
	 * @Column(type="text")
	 */
	protected $airports;


	public function __construct() {

	}


	/**
	 * @param Currency $defaultCurrency
	 * @return Country
	 */
	public function setDefaultCurrency(Currency  $defaultCurrency) {
		$this->defaultCurrency = $defaultCurrency;
		return $this;
	}


	/**
	 * @return Currency
	 */
	public function getDefaultCurrency() {
		return $this->defaultCurrency;
	}


	/**
	 * @param Currency $currencies
	 * @return Country
	 */
	public function setCurrencies(Currency  $currencies) {
		$this->currencies = $currencies;
		return $this;
	}


	/**
	 * @return Currency
	 */
	public function getCurrencies() {
		return $this->currencies;
	}


	/**
	 * @param Dictionary\Language $defaultLanguage
	 * @return Country
	 */
	public function setDefaultLanguage(Dictionary\Language  $defaultLanguage) {
		$this->defaultLanguage = $defaultLanguage;
		return $this;
	}


	/**
	 * @return Dictionary\Language
	 */
	public function getDefaultLanguage() {
		return $this->defaultLanguage;
	}


	/**
	 * @param integer $population
	 * @return Country
	 */
	public function setPopulation($population) {
		$this->population = $population;
		return $this;
	}


	/**
	 * @return integer
	 */
	public function getPopulation() {
		return $this->population;
	}


	/**
	 * @param string $phonePrefix
	 * @return Country
	 */
	public function setPhonePrefix($phonePrefix) {
		$this->phonePrefix = $phonePrefix;
		return $this;
	}


	/**
	 * @return string
	 */
	public function getPhonePrefix() {
		return $this->phonePrefix;
	}


	/**
	 * @param Location $neighbours
	 * @return Country
	 */
	public function setNeighbours(Location  $neighbours) {
		$this->neighbours = $neighbours;
		return $this;
	}


	/**
	 * @return Location
	 */
	public function getNeighbours() {
		return $this->neighbours;
	}


	/**
	 * @param Contact\Contact $fbGroup
	 * @return Country
	 */
	public function setFbGroup(Contact\Contact  $fbGroup) {
		$this->fbGroup = $fbGroup;
		return $this;
	}


	/**
	 * @return Contact\Contact
	 */
	public function getFbGroup() {
		return $this->fbGroup;
	}


	/**
	 * @param string $capitalCity
	 * @return Country
	 */
	public function setCapitalCity($capitalCity) {
		$this->capitalCity = $capitalCity;
		return $this;
	}


	/**
	 * @return string
	 */
	public function getCapitalCity() {
		return $this->capitalCity;
	}


	/**
	 * @param Contact\Contact $phoneNumberEmergency
	 * @return Country
	 */
	public function setPhoneNumberEmergency(Contact\Contact  $phoneNumberEmergency) {
		$this->phoneNumberEmergency = $phoneNumberEmergency;
		return $this;
	}


	/**
	 * @return Contact\Contact
	 */
	public function getPhoneNumberEmergency() {
		return $this->phoneNumberEmergency;
	}


	/**
	 * @param Contact\Contact $phoneNumberPolice
	 * @return Country
	 */
	public function setPhoneNumberPolice(Contact\Contact  $phoneNumberPolice) {
		$this->phoneNumberPolice = $phoneNumberPolice;
		return $this;
	}


	/**
	 * @return Contact\Contact
	 */
	public function getPhoneNumberPolice() {
		return $this->phoneNumberPolice;
	}


	/**
	 * @param Contact\Contact $phoneNumberMedical
	 * @return Country
	 */
	public function setPhoneNumberMedical(Contact\Contact  $phoneNumberMedical) {
		$this->phoneNumberMedical = $phoneNumberMedical;
		return $this;
	}


	/**
	 * @return Contact\Contact
	 */
	public function getPhoneNumberMedical() {
		return $this->phoneNumberMedical;
	}


	/**
	 * @param Contact\Contact $phoneNumberFire
	 * @return Country
	 */
	public function setPhoneNumberFire(Contact\Contact  $phoneNumberFire) {
		$this->phoneNumberFire = $phoneNumberFire;
		return $this;
	}


	/**
	 * @return Contact\Contact
	 */
	public function getPhoneNumberFire() {
		return $this->phoneNumberFire;
	}


	/**
	 * @param Contact\Contact $wikipediaLink
	 * @return Country
	 */
	public function setWikipediaLink(Contact\Contact  $wikipediaLink) {
		$this->wikipediaLink = $wikipediaLink;
		return $this;
	}


	/**
	 * @return Contact\Contact
	 */
	public function getWikipediaLink() {
		return $this->wikipediaLink;
	}


	/**
	 * @param string $drivingSide
	 * @return Country
	 */
	public function setDrivingSide($drivingSide) {
		$this->drivingSide = $drivingSide;
		return $this;
	}


	/**
	 * @return string
	 */
	public function getDrivingSide() {
		return $this->drivingSide;
	}


	/**
	 * @param price $pricesPizza
	 * @return Country
	 */
	public function setPricesPizza($pricesPizza) {
		$this->pricesPizza = $pricesPizza;
		return $this;
	}


	/**
	 * @return price
	 */
	public function getPricesPizza() {
		return $this->pricesPizza;
	}


	/**
	 * @param price $pricesDinner
	 * @return Country
	 */
	public function setPricesDinner($pricesDinner) {
		$this->pricesDinner = $pricesDinner;
		return $this;
	}


	/**
	 * @return price
	 */
	public function getPricesDinner() {
		return $this->pricesDinner;
	}


	/**
	 * @param text $airports
	 * @return Country
	 */
	public function setAirports($airports) {
		$this->airports = $airports;
		return $this;
	}


	/**
	 * @return text
	 */
	public function getAirports() {
		return $this->airports;
	}

}
