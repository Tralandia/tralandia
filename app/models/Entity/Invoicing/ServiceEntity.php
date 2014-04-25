<?php

namespace Entity\Invoicing;

use Entity\Phrase;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="invoicing_service")
 * @method setType(ServiceType $type)
 * @method ServiceType getType()
 * @method setDuration(\Entity\Invoicing\ServiceDuration $duration)
 * @method \Entity\Invoicing\ServiceDuration getDuration()
 * @method setPriceDefault($priceDefault)
 * @method getPriceDefault()
 * @method setPriceCurrent($priceCurrent)
 * @method getPriceCurrent()
 * @method setCurrency(\Entity\Currency $currency)
 * @method \Entity\Currency getCurrency()
 * @method setCompany(Company $company)
 * @method Company getCompany()
 * @method setPrimaryLocation(\Entity\Location\Location $primaryLocation)
 * @method \Entity\Location\Location|NULL getPrimaryLocation()
 */
class Service extends \Entity\BaseEntity {


	/**
	 * @var ServiceType
	 * @ORM\ManyToOne(targetEntity="ServiceType")
	 */
	protected $type;

	/**
	 * @var ServiceDuration
	 * @ORM\ManyToOne(targetEntity="ServiceDuration")
	 */
	protected $duration;

	/**
	 * @var float
	 * @ORM\Column(type="float", nullable=true)
	 */
	protected $priceDefault;

	/**
	 * @var float
	 * @ORM\Column(type="float", nullable=true)
	 */
	protected $priceCurrent;

	/**
	 * @var \Entity\Currency
	 * @ORM\ManyToOne(targetEntity="Entity\Currency")
	 */
	protected $currency;

	/**
	 * @var Company
	 * @ORM\ManyToOne(targetEntity="Company")
	 */
	protected $company;

	/**
	 * @var \Entity\Location\Location
	 * @ORM\ManyToOne(targetEntity="Entity\Location\Location")
	 */
	protected $primaryLocation;

}
