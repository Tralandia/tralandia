<?php

namespace Entity\Expense;

use Entity\Location;
use Entity\Company;
use Entity\User;
use Doctrine\ORM\Mapping as ORM;
use	Extras\Annotation as EA;

/**
 * @ORM\Entity()
 * @ORM\Table(name="expense")
 * @EA\Primary(key="id", value="name")
 */
class Expense extends \Entity\BaseEntity {

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $name;

	/**
	 * @var price
	 * @ORM\Column(type="float")
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

	//@entity-generator-code --- NEMAZAT !!!

	/* ----------------------------- Methods ----------------------------- */		
	public function __construct()
	{
		parent::__construct();
	}
		
	/**
	 * @param string
	 * @return \Entity\Expense\Expense
	 */
	public function setName($name)
	{
		$this->name = $name;

		return $this;
	}
		
	/**
	 * @return \Entity\Expense\Expense
	 */
	public function unsetName()
	{
		$this->name = NULL;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getName()
	{
		return $this->name;
	}
		
	/**
	 * @param float
	 * @return \Entity\Expense\Expense
	 */
	public function setAmount($amount)
	{
		$this->amount = $amount;

		return $this;
	}
		
	/**
	 * @return float|NULL
	 */
	public function getAmount()
	{
		return $this->amount;
	}
		
	/**
	 * @param \Entity\Expense\Type
	 * @return \Entity\Expense\Expense
	 */
	public function setType(\Entity\Expense\Type $type)
	{
		$this->type = $type;

		return $this;
	}
		
	/**
	 * @return \Entity\Expense\Expense
	 */
	public function unsetType()
	{
		$this->type = NULL;

		return $this;
	}
		
	/**
	 * @return \Entity\Expense\Type|NULL
	 */
	public function getType()
	{
		return $this->type;
	}
		
	/**
	 * @param \Entity\Location\Location
	 * @return \Entity\Expense\Expense
	 */
	public function setCountry(\Entity\Location\Location $country)
	{
		$this->country = $country;

		return $this;
	}
		
	/**
	 * @return \Entity\Expense\Expense
	 */
	public function unsetCountry()
	{
		$this->country = NULL;

		return $this;
	}
		
	/**
	 * @return \Entity\Location\Location|NULL
	 */
	public function getCountry()
	{
		return $this->country;
	}
		
	/**
	 * @param \Entity\Company\Company
	 * @return \Entity\Expense\Expense
	 */
	public function setCompany(\Entity\Company\Company $company)
	{
		$this->company = $company;

		return $this;
	}
		
	/**
	 * @return \Entity\Expense\Expense
	 */
	public function unsetCompany()
	{
		$this->company = NULL;

		return $this;
	}
		
	/**
	 * @return \Entity\Company\Company|NULL
	 */
	public function getCompany()
	{
		return $this->company;
	}
		
	/**
	 * @param \Entity\Company\BankAccount
	 * @return \Entity\Expense\Expense
	 */
	public function setBankAccount(\Entity\Company\BankAccount $bankAccount)
	{
		$this->bankAccount = $bankAccount;

		return $this;
	}
		
	/**
	 * @return \Entity\Expense\Expense
	 */
	public function unsetBankAccount()
	{
		$this->bankAccount = NULL;

		return $this;
	}
		
	/**
	 * @return \Entity\Company\BankAccount|NULL
	 */
	public function getBankAccount()
	{
		return $this->bankAccount;
	}
		
	/**
	 * @param \Entity\User\User
	 * @return \Entity\Expense\Expense
	 */
	public function setUser(\Entity\User\User $user)
	{
		$this->user = $user;

		return $this;
	}
		
	/**
	 * @return \Entity\Expense\Expense
	 */
	public function unsetUser()
	{
		$this->user = NULL;

		return $this;
	}
		
	/**
	 * @return \Entity\User\User|NULL
	 */
	public function getUser()
	{
		return $this->user;
	}
}