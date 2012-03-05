<?php

namespace Entities\Company;

use Entities\Company;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;


/**
 * @ORM\Entity()
 * @ORM\Table(name="company_office")
 */
class Office extends \BaseEntityDetails {

	/**
	 * @var Collection
	 * @ORM\ManyToMany(type="Company")
	 */
	protected $company;

	/**
	 * @var address
	 * @ORM\ManyToMany(type="address")
	 */
	protected $address;


	public function __construct() {

	}


	/**
	 * @param Company $company
	 * @return Office
	 */
	public function setCompany(Company  $company) {
		$this->company = $company;
		return $this;
	}


	/**
	 * @return Company
	 */
	public function getCompany() {
		return $this->company;
	}


	/**
	 * @param address $address
	 * @return Office
	 */
	public function setAddress($address) {
		$this->address = $address;
		return $this;
	}


	/**
	 * @return address
	 */
	public function getAddress() {
		return $this->address;
	}

}
