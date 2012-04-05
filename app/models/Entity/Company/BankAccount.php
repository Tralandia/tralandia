<?php

namespace Entity\Company;

use Entity\Location;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="company_bankaccount")
 */
class BankAccount extends \Entity\BaseEntity {

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Entity\Location\Location", mappedBy="bankAccounts")
	 */
	protected $countries;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Company", inversedBy="bankAccounts")
	 */
	protected $company;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $bankName;

	/**
	 * @var address
	 * @ORM\Column(type="address")
	 */
	protected $bankAddress;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $bankSwift;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $accountNumber;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $accountName;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $accountIban;

	/**
	 * @var text
	 * @ORM\Column(type="text", nullable=true)
	 */
	protected $notes;

	//@entity-generator-code

}