<?php

namespace Entities\Expense;

use Entities\Location;
use Entities\Company;
use Entities\User;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="expense_expense")
 */
class Expense extends \BaseEntity {

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
	 * @ORM\ManyToOne(targetEntity="Location\Location")
	 */
	protected $country;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Company\Company")
	 */
	protected $company;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Company\BankAccount")
	 */
	protected $bankAccount;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="User\User")
	 */
	protected $user;

}