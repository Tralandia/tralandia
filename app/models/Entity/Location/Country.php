<?php

namespace Entity\Location;

use Entity\Contact;
use Entity\Dictionary;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="location_country")
 */
class Country extends \Entity\BaseEntityDetails {

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
	 * @ORM\ManyToOne(targetEntity="Entity\Currency", cascade={"persist"})
	 */
	protected $defaultCurrency;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Entity\Currency", mappedBy="countries", cascade={"persist"})
	 */
	protected $currencies;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entity\Dictionary\Language", cascade={"persist"})
	 */
	protected $defaultLanguage;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Entity\Dictionary\Language", mappedBy="countries", cascade={"persist"})
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
  //    * @ManyToMany(targetEntity="Entity\Location\Country", mappedBy="myNeighbours")
  //    */
  //   private $neighbours;

  //   /**
	 // * @var Collection
  //    * @ManyToMany(targetEntity="Entity\Location\Country", inversedBy="neighbours")
  //     */
  //   private $myNeighbours;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entity\Contact\Contact")
	 */
	protected $facebookGroup;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $capitalCity;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entity\Contact\Contact")
	 */
	protected $phoneNumberEmergency;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entity\Contact\Contact")
	 */
	protected $phoneNumberPolice;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entity\Contact\Contact")
	 */
	protected $phoneNumberMedical;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entity\Contact\Contact")
	 */
	protected $phoneNumberFire;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entity\Contact\Contact")
	 */
	protected $wikipediaLink;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Entity\Contact\Contact", mappedBy="countries", cascade={"persist"})
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

	//@entity-generator-code


}