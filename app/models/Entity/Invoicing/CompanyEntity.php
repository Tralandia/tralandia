<?php

namespace Entity\Invoicing;

use Entity\Phrase;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="invoicing_company")
 *
 * @method setName($name)
 * @method getName()
 * @method setSlug($slug)
 * @method getSlug()
 * @method setAddress($address)
 * @method getAddress()
 * @method setAddress2($address2)
 * @method getAddress2()
 * @method setPostcode($postcode)
 * @method getPostcode()
 * @method setLocality(\Entity\Location\Location $locality)
 * @method \Entity\Location\Location getLocality()
 * @method setPrimaryLocation(\Entity\Location\Location $primaryLocation)
 * @method \Entity\Location\Location getPrimaryLocation()
 * @method setCompanyId($companyId)
 * @method getCompanyId()
 * @method setCompanyVatId($companyVatId)
 * @method getCompanyVatId()
 * @method setVat($vat)
 * @method getVat()
 * @method setRegistrator($registrator)
 * @method getRegistrator()
 * @method setInEu($inEu)
 * @method getInEu()
 */
class Company extends \Entity\BaseEntity {

	const SLUG_ZERO = 'zero';

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $name;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $slug;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $address;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $address2;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $postcode;

	/**
	 * @var \Entity\Location\Location
	 * @ORM\ManyToOne(targetEntity="Entity\Location\Location")
	 */
	protected $locality;

	/**
	 * @var \Entity\Location\Location
	 * @ORM\ManyToOne(targetEntity="Entity\Location\Location")
	 */
	protected $primaryLocation;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $companyId; // aka ICO

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $companyVatId;

	/**
	 * @var float
	 * @ORM\Column(type="float", nullable=true)
	 */
	protected $vat;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $registrator;

	/**
	 * @var boolean
	 * @ORM\Column(type="boolean")
	 */
	protected $inEu = TRUE;

}
