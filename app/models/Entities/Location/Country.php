<?php

namespace Entities\Location;

use Entities\Contact;
use Entities\Dictionary;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="location_country")
 */
class Country extends \Entities\BaseEntityDetails {

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entities\Currency")
	 */
	protected $defaultCurrency;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Entities\Currency", mappedBy="countries")
	 */
	protected $currencies;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entities\Dictionary\Language")
	 */
	protected $defaultLanguage;

	/**
	 * @var integer
	 * @ORM\Column(type="integer")
	 */
	protected $population;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $phonePrefix;

	// /**
	//  * @var Collection
	//  * @ORM\OneToMany(targetEntity="Location")
	//  */
	// protected $neighbours;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entities\Contact\Contact")
	 */
	protected $facebookGroup;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $capitalCity;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entities\Contact\Contact")
	 */
	protected $phoneNumberEmergency;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entities\Contact\Contact")
	 */
	protected $phoneNumberPolice;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entities\Contact\Contact")
	 */
	protected $phoneNumberMedical;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entities\Contact\Contact")
	 */
	protected $phoneNumberFire;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entities\Contact\Contact")
	 */
	protected $wikipediaLink;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $drivingSide;

	/**
	 * @var price
	 * @ORM\Column(type="price")
	 */
	protected $pricesPizza;

	/**
	 * @var price
	 * @ORM\Column(type="price")
	 */
	protected $pricesDinner;

	/**
	 * @var text
	 * @ORM\Column(type="text")
	 */
	protected $airports;

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Location", mappedBy="country")
	 */
	protected $location;

	/* ----------------------------- Methods ----------------------------- */


	public function __construct() {
		parent::__construct();

		$this->currencies = new \Doctrine\Common\Collections\ArrayCollection;
	}

	/**
	 * @param \Entities\Currency
	 * @return \Entities\Location\Country
	 */
	public function setDefaultCurrency(\Entities\Currency $defaultCurrency) {
		$this->defaultCurrency = $defaultCurrency;

		return $this;
	}

	/**
	 * @return \Entities\Location\Country
	 */
	public function unsetDefaultCurrency() {
		$this->defaultCurrency = NULL;

		return $this;
	}

	/**
	 * @return \Entities\Currency|NULL
	 */
	public function getDefaultCurrency() {
		return $this->defaultCurrency;
	}

	/**
	 * @param \Entities\Currency
	 * @return \Entities\Location\Country
	 */
	public function addCurrency(\Entities\Currency $currency) {
		if(!$this->currencies->contains($currency)) {
			$this->currencies->add($currency);
		}
		$currency->addCountry($this);

		return $this;
	}

	/**
	 * @param \Entities\Currency
	 * @return \Entities\Location\Country
	 */
	public function removeCurrency(\Entities\Currency $currency) {
		if($this->currencies->contains($currency)) {
			$this->currencies->removeElement($currency);
		}
		$currency->removeCountry($this);

		return $this;
	}

	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection of \Entities\Currency
	 */
	public function getCurrencies() {
		return $this->currencies;
	}

	/**
	 * @param \Entities\Dictionary\Language
	 * @return \Entities\Location\Country
	 */
	public function setDefaultLanguage(\Entities\Dictionary\Language $defaultLanguage) {
		$this->defaultLanguage = $defaultLanguage;

		return $this;
	}

	/**
	 * @return \Entities\Location\Country
	 */
	public function unsetDefaultLanguage() {
		$this->defaultLanguage = NULL;

		return $this;
	}

	/**
	 * @return \Entities\Dictionary\Language|NULL
	 */
	public function getDefaultLanguage() {
		return $this->defaultLanguage;
	}

	/**
	 * @param integer
	 * @return \Entities\Location\Country
	 */
	public function setPopulation($population) {
		$this->population = $population;

		return $this;
	}

	/**
	 * @return integer|NULL
	 */
	public function getPopulation() {
		return $this->population;
	}

	/**
	 * @param string
	 * @return \Entities\Location\Country
	 */
	public function setPhonePrefix($phonePrefix) {
		$this->phonePrefix = $phonePrefix;

		return $this;
	}

	/**
	 * @return \Entities\Location\Country
	 */
	public function unsetPhonePrefix() {
		$this->phonePrefix = NULL;

		return $this;
	}

	/**
	 * @return string|NULL
	 */
	public function getPhonePrefix() {
		return $this->phonePrefix;
	}

	/**
	 * @param \Entities\Contact\Contact
	 * @return \Entities\Location\Country
	 */
	public function setFacebookGroup(\Entities\Contact\Contact $facebookGroup) {
		$this->facebookGroup = $facebookGroup;

		return $this;
	}

	/**
	 * @return \Entities\Location\Country
	 */
	public function unsetFacebookGroup() {
		$this->facebookGroup = NULL;

		return $this;
	}

	/**
	 * @return \Entities\Contact\Contact|NULL
	 */
	public function getFacebookGroup() {
		return $this->facebookGroup;
	}

	/**
	 * @param string
	 * @return \Entities\Location\Country
	 */
	public function setCapitalCity($capitalCity) {
		$this->capitalCity = $capitalCity;

		return $this;
	}

	/**
	 * @return \Entities\Location\Country
	 */
	public function unsetCapitalCity() {
		$this->capitalCity = NULL;

		return $this;
	}

	/**
	 * @return string|NULL
	 */
	public function getCapitalCity() {
		return $this->capitalCity;
	}

	/**
	 * @param \Entities\Contact\Contact
	 * @return \Entities\Location\Country
	 */
	public function setPhoneNumberEmergency(\Entities\Contact\Contact $phoneNumberEmergency) {
		$this->phoneNumberEmergency = $phoneNumberEmergency;

		return $this;
	}

	/**
	 * @return \Entities\Location\Country
	 */
	public function unsetPhoneNumberEmergency() {
		$this->phoneNumberEmergency = NULL;

		return $this;
	}

	/**
	 * @return \Entities\Contact\Contact|NULL
	 */
	public function getPhoneNumberEmergency() {
		return $this->phoneNumberEmergency;
	}

	/**
	 * @param \Entities\Contact\Contact
	 * @return \Entities\Location\Country
	 */
	public function setPhoneNumberPolice(\Entities\Contact\Contact $phoneNumberPolice) {
		$this->phoneNumberPolice = $phoneNumberPolice;

		return $this;
	}

	/**
	 * @return \Entities\Location\Country
	 */
	public function unsetPhoneNumberPolice() {
		$this->phoneNumberPolice = NULL;

		return $this;
	}

	/**
	 * @return \Entities\Contact\Contact|NULL
	 */
	public function getPhoneNumberPolice() {
		return $this->phoneNumberPolice;
	}

	/**
	 * @param \Entities\Contact\Contact
	 * @return \Entities\Location\Country
	 */
	public function setPhoneNumberMedical(\Entities\Contact\Contact $phoneNumberMedical) {
		$this->phoneNumberMedical = $phoneNumberMedical;

		return $this;
	}

	/**
	 * @return \Entities\Location\Country
	 */
	public function unsetPhoneNumberMedical() {
		$this->phoneNumberMedical = NULL;

		return $this;
	}

	/**
	 * @return \Entities\Contact\Contact|NULL
	 */
	public function getPhoneNumberMedical() {
		return $this->phoneNumberMedical;
	}

	/**
	 * @param \Entities\Contact\Contact
	 * @return \Entities\Location\Country
	 */
	public function setPhoneNumberFire(\Entities\Contact\Contact $phoneNumberFire) {
		$this->phoneNumberFire = $phoneNumberFire;

		return $this;
	}

	/**
	 * @return \Entities\Location\Country
	 */
	public function unsetPhoneNumberFire() {
		$this->phoneNumberFire = NULL;

		return $this;
	}

	/**
	 * @return \Entities\Contact\Contact|NULL
	 */
	public function getPhoneNumberFire() {
		return $this->phoneNumberFire;
	}

	/**
	 * @param \Entities\Contact\Contact
	 * @return \Entities\Location\Country
	 */
	public function setWikipediaLink(\Entities\Contact\Contact $wikipediaLink) {
		$this->wikipediaLink = $wikipediaLink;

		return $this;
	}

	/**
	 * @return \Entities\Location\Country
	 */
	public function unsetWikipediaLink() {
		$this->wikipediaLink = NULL;

		return $this;
	}

	/**
	 * @return \Entities\Contact\Contact|NULL
	 */
	public function getWikipediaLink() {
		return $this->wikipediaLink;
	}

	/**
	 * @param string
	 * @return \Entities\Location\Country
	 */
	public function setDrivingSide($drivingSide) {
		$this->drivingSide = $drivingSide;

		return $this;
	}

	/**
	 * @return \Entities\Location\Country
	 */
	public function unsetDrivingSide() {
		$this->drivingSide = NULL;

		return $this;
	}

	/**
	 * @return string|NULL
	 */
	public function getDrivingSide() {
		return $this->drivingSide;
	}

	/**
	 * @param \Extras\Types\Price
	 * @return \Entities\Location\Country
	 */
	public function setPricesPizza(\Extras\Types\Price $pricesPizza) {
		$this->pricesPizza = $pricesPizza;

		return $this;
	}

	/**
	 * @return \Extras\Types\Price|NULL
	 */
	public function getPricesPizza() {
		return $this->pricesPizza;
	}

	/**
	 * @param \Extras\Types\Price
	 * @return \Entities\Location\Country
	 */
	public function setPricesDinner(\Extras\Types\Price $pricesDinner) {
		$this->pricesDinner = $pricesDinner;

		return $this;
	}

	/**
	 * @return \Extras\Types\Price|NULL
	 */
	public function getPricesDinner() {
		return $this->pricesDinner;
	}

	/**
	 * @param string
	 * @return \Entities\Location\Country
	 */
	public function setAirports($airports) {
		$this->airports = $airports;

		return $this;
	}

	/**
	 * @return string|NULL
	 */
	public function getAirports() {
		return $this->airports;
	}

	/**
	 * @todo location
	 */
	public function todoLocation() {

	}

}