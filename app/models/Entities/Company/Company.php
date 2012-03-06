<?php

namespace Entities\Company;

use Entities\Company;
use Entities\Dictionary;
use Entities\Invoicing;
use Entities\Location;
use Entities\Medium;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="company_company")
 */
class Company extends \BaseEntityDetails {

	/**
	 * @var Collection
	 * @ORM\OneToMany(type="BankAccount", mappedBy="company")
	 */
	protected $accounts;

	/**
	 * @var Collection
	 * @ORM\OneToMany(targetEntity="Location\Location")
	 */
	protected $countries;

	/**
	 * @var Collection
	 * @ORM\OneToMany(targetEntity="Office", mappedBy="company")
	 */
	protected $offices;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $name;

	/**
	 * @var address
	 * @ORM\Column(type="address")
	 */
	protected $address;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $companyId;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $companyVatId;

	/**
	 * @var decimal
	 * @ORM\Column(type="decimal")
	 */
	protected $vat;

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Dictionary\Phrase")
	 */
	protected $registrator;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Medium\Medium")
	 */
	protected $signature;

	/**
	 * @var Collection
	 * @ORM\OneToMany(targetEntity="Invoicing\Invoice", mappedBy="invoicingCompany")
	 */
	protected $invoices;

}