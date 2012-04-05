<?php

namespace Entity\Expense;

use Entity\Location;
use Entity\Company;
use Entity\User;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="expense_expense")
 */
class Expense extends \Entity\BaseEntity {

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $name;

	/**
	 * @var price
	 * @ORM\Column(type="price")
	 */
	protected $amount;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Type")
	 */
	protected $type;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entity\Location\Location")
	 */
	protected $country;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entity\Company\Company")
	 */
	protected $company;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entity\Company\BankAccount")
	 */
	protected $bankAccount;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entity\User\User")
	 */
	protected $user;

	//@entity-generator-code

}