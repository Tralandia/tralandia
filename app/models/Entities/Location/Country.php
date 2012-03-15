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

}