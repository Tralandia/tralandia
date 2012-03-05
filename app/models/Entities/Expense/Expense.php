<?php

namespace Expense;

use Expense;
use Location;
use Doctrine\Common\Collections\Collection
use Doctrine\Common\Collections\ArrayCollection


/**
 * @Entity()
 * @Table(name="expense_expense")
 */
class Expense extends \BaseEntity {

	/**
	 * @var string
	 * @Column(type="string", nullable=true)
	 */
	protected $name;

	/**
	 * @var price
	 * @Column(type="price")
	 */
	protected $amount;

	/**
	 * @var Collection
	 * @Column(type="Type")
	 */
	protected $type;

	/**
	 * @var Collection
	 * @OneToOne(targetEntity="Location\Location")
	 */
	protected $country;

	/**
	 * @var Collection
	 * @Column(type="")
	 */
	protected $company;


	public function __construct() {

	}


	/**
	 * @param string $name
	 * @return Expense
	 */
	public function setName($name) {
		$this->name = $name;
		return $this;
	}


	/**
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}


	/**
	 * @param price $amount
	 * @return Expense
	 */
	public function setAmount($amount) {
		$this->amount = $amount;
		return $this;
	}


	/**
	 * @return price
	 */
	public function getAmount() {
		return $this->amount;
	}


	/**
	 * @param Type $type
	 * @return Expense
	 */
	public function setType(Type  $type) {
		$this->type = $type;
		return $this;
	}


	/**
	 * @return Type
	 */
	public function getType() {
		return $this->type;
	}


	/**
	 * @param Location\Location $country
	 * @return Expense
	 */
	public function setCountry(Location\Location  $country) {
		$this->country = $country;
		return $this;
	}


	/**
	 * @return Location\Location
	 */
	public function getCountry() {
		return $this->country;
	}


	/**
	 * @param  $company
	 * @return Expense
	 */
	public function setCompany(  $company) {
		$this->company = $company;
		return $this;
	}


	/**
	 * @return
	 */
	public function getCompany() {
		return $this->company;
	}

}
