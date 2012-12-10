<?php

namespace Entity\Company;

use Entity\Phrase;
use Entity\Invoice;
use Entity\Location;
use Entity\Medium;
use Doctrine\ORM\Mapping as ORM;
use	Extras\Annotation as EA;

/**
 * @ORM\Entity()
 * @ORM\Table(name="company")
 * @EA\Primary(key="id", value="name")
 */
class Company extends \Entity\BaseEntityDetails {

	/**
	 * @var Collection
	 * @ORM\OneToMany(targetEntity="BankAccount", mappedBy="company", cascade={"persist", "remove"})
	 */
	protected $bankAccounts;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Entity\Location\Location", mappedBy="companies", cascade={"persist"})
	 */
	protected $countries;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $name;

	/**
	 * @var address
	 * @ORM\Column(type="address", nullable=true)
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
	 * @var float
	 * @ORM\Column(type="float", nullable=true)
	 */
	protected $vat;

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Entity\Phrase\Phrase", cascade={"persist", "remove"})
	 */
	protected $registrator;

	/**
	 * @var Collection
	 * @ORM\OneToMany(targetEntity="Entity\Invoice\Invoice", mappedBy="company")
	 */
	protected $invoices;

								//@entity-generator-code --- NEMAZAT !!!

	/* ----------------------------- Methods ----------------------------- */		

}