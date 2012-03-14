<?php

namespace Entities\Company;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="company_office")
 */
class Office extends \BaseEntityDetails {

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Company", inversedBy="offices")
	 */
	protected $company;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Entities\Location\Location", mappedBy="offices")
	 */
	protected $countries;

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Entities\Medium\Medium")
	 */
	protected $signature;
	/**
	 * @var address
	 * @ORM\Column(type="address")
	 */
	protected $address;

}