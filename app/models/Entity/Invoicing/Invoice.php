<?php

namespace Entity\Invoicing;

use Entity\Company;
use Entity\Dictionary;
use Entity\Invoicing;
use Entity\Rental;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="invoicing_invoice")
 */
class Invoice extends \Entity\BaseEntity {

	/**
	 * @var Collection
	 * @ORM\OneToMany(targetEntity="Item", mappedBy="invoice")
	 */
	protected $items;

	/**
	 * @var integer
	 * @ORM\Column(type="integer")
	 */
	protected $invoiceNumber;

	/**
	 * @var integer
	 * @ORM\Column(type="integer")
	 */
	protected $invoiceVariableNumber;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entity\Company\Company", inversedBy="invoices")
	 */
	protected $invoicingCompany;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entity\Rental\Rental", inversedBy="invoices")
	 */
	protected $rental;

	/**
	 * @var datetime
	 * @ORM\Column(type="datetime")
	 */
	protected $due;

	/**
	 * @var datetime
	 * @ORM\Column(type="datetime")
	 */
	protected $paid;

	/**
	 * @var boolean
	 * @ORM\Column(type="boolean")
	 */
	protected $checked;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $clientName;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $clientPhone;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $clientEmail;

	/**
	 * @var url
	 * @ORM\Column(type="url")
	 */
	protected $clientUrl;

	/**
	 * @var address
	 * @ORM\Column(type="address")
	 */
	protected $clientAddress;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entity\Dictionary\Language")
	 */
	protected $clientLanguage;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $clientCompanyName;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $clientCompanyId;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $clientCompanyVatId;

	/**
	 * @var decimal
	 * @ORM\Column(type="decimal")
	 */
	protected $vat;

	/**
	 * @var decimal
	 * @ORM\Column(type="decimal")
	 */
	protected $exchangeRate;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $createdBy;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $referrer;

	/**
	 * @var decimal
	 * @ORM\Column(type="decimal")
	 */
	protected $referrerCommission;

	/**
	 * @var json
	 * @ORM\Column(type="json")
	 */
	protected $paymentInfo;

	//@entity-generator-code

}