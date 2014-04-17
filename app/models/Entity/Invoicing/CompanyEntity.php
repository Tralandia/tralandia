<?php

namespace Entity\Invoicing;

use Entity\Phrase;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="invoicing_company")
 */
class Company extends \Entity\BaseEntity {

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $name;

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
