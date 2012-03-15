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
class Expense extends \Entities\BaseEntity {

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
	 * @ORM\ManyToOne(targetEntity="Entities\Location\Location")
	 */
	protected $country;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entities\Company\Company")
	 */
	protected $company;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entities\Company\BankAccount")
	 */
	protected $bankAccount;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entities\User\User")
	 */
	protected $user;

}