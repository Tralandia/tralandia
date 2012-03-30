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
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $status;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $iso;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $iso3;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entities\Currency", cascade={"persist"})
	 */
	protected $defaultCurrency;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Entities\Currency", mappedBy="countries", cascade={"persist"})
	 */
	protected $currencies;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entities\Dictionary\Language", cascade={"persist"})
	 */
	protected $defaultLanguage;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Entities\Dictionary\Language", mappedBy="countries", cascade={"persist"})
	 */
	protected $languages;

	/**
	 * @var integer
	 * @ORM\Column(type="integer", nullable=true)
	 */
	protected $population;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $phonePrefix;

  //   /**
	 // * @var Collection
  //    * @ManyToMany(targetEntity="Entities\Location\Country", mappedBy="myNeighbours")
  //    */
  //   private $neighbours;

  //   /**
	 // * @var Collection
  //    * @ManyToMany(targetEntity="Entities\Location\Country", inversedBy="neighbours")
  //     */
  //   private $myNeighbours;

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
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Entities\Contact\Contact", mappedBy="countries", cascade={"persist"})
	 */
	protected $contacts;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $drivingSide;

	/**
	 * @var price
	 * @ORM\Column(type="price", nullable=true)
	 */
	protected $pricesPizza;

	/**
	 * @var price
	 * @ORM\Column(type="price", nullable=true)
	 */
	protected $pricesDinner;

	/**
	 * @var text
	 * @ORM\Column(type="text", nullable=true)
	 */
	protected $airports;

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Location", inversedBy="country", cascade={"persist"})
	 */
	protected $location;

	/* ----------------------------- Methods ----------------------------- */


	public function __construct() {
		parent::__construct();

		$this->currencies = new \Doctrine\Common\Collections\ArrayCollection;
		$this->languages = new \Doctrine\Common\Collections\ArrayCollection;
		$this->contacts = new \Doctrine\Common\Collections\ArrayCollection;
	}

	/**
	 * @param string
	 * @return \Entities\Location\Country
	 */
	public function setStatus($status) {
		$this->status = $status;

		return $this;
	}

	/**
	 * @return \Entities\Location\Country
	 */
	public function unsetStatus() {
		$this->status = NULL;

		return $this;
	}

	/**
	 * @return string|NULL
	 */
	public function getStatus() {
		return $this->status;
	}

	/**
	 * @param string
	 * @return \Entities\Location\Country
	 */
	public function setIso($iso) {
		$this->iso = $iso;

		return $this;
	}

	/**
	 * @return \Entities\Location\Country
	 */
	public function unsetIso() {
		$this->iso = NULL;

		return $this;
	}

	/**
	 * @return string|NULL
	 */
	public function getIso() {
		return $this->iso;
	}

	/**
	 * @param string
	 * @return \Entities\Location\Country
	 */
	public function setIso3($iso3) {
		$this->iso3 = $iso3;

		return $this;
	}

	/**
	 * @return \Entities\Location\Country
	 */
	public function unsetIso3() {
		$this->iso3 = NULL;

		return $this;
	}

	/**
	 * @return string|NULL
	 */
	public function getIso3() {
		return $this->iso3;
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
	 * @param \Entities\Dictionary\Language
	 * @return \Entities\Location\Country
	 */
	public function addLanguage(\Entities\Dictionary\Language $language) {
		if(!$this->languages->contains($language)) {
			$this->languages->add($language);
		}
		$language->addCountry($this);

		return $this;
	}

	/**
	 * @param \Entities\Dictionary\Language
	 * @return \Entities\Location\Country
	 */
	public function removeLanguage(\Entities\Dictionary\Language $language) {
		if($this->languages->contains($language)) {
			$this->languages->removeElement($language);
		}
		$language->removeCountry($this);

		return $this;
	}

	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection of \Entities\Dictionary\Language
	 */
	public function getLanguages() {
		return $this->languages;
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
	 * @return \Entities\Location\Country
	 */
	public function unsetPopulation() {
		$this->population = NULL;

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
	 * @param \Entities\Contact\Contact
	 * @return \Entities\Location\Country
	 */
	public function addContact(\Entities\Contact\Contact $contact) {
		if(!$this->contacts->contains($contact)) {
			$this->contacts->add($contact);
		}
		$contact->addCountry($this);

		return $this;
	}

	/**
	 * @param \Entities\Contact\Contact
	 * @return \Entities\Location\Country
	 */
	public function removeContact(\Entities\Contact\Contact $contact) {
		if($this->contacts->contains($contact)) {
			$this->contacts->removeElement($contact);
		}
		$contact->removeCountry($this);

		return $this;
	}

	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection of \Entities\Contact\Contact
	 */
	public function getContacts() {
		return $this->contacts;
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
	 * @return \Entities\Location\Country
	 */
	public function unsetPricesPizza() {
		$this->pricesPizza = NULL;

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
	 * @return \Entities\Location\Country
	 */
	public function unsetPricesDinner() {
		$this->pricesDinner = NULL;

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
	 * @return \Entities\Location\Country
	 */
	public function unsetAirports() {
		$this->airports = NULL;

		return $this;
	}

	/**
	 * @return string|NULL
	 */
	public function getAirports() {
		return $this->airports;
	}

	/**
	 * @warning Bacha inverzna strana!
	 * @param \Entities\Location\Location
	 * @return \Entities\Location\Country
	 */
	public function setLocation(\Entities\Location\Location $location) {
		$this->location = $location;

		return $this;
	}

	/**
	 * @return \Entities\Location\Location|NULL
	 */
	public function getLocation() {
		return $this->location;
	}

}