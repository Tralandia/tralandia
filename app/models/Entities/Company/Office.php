<?php

namespace Entities\Company;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="company_office")
 */
class Office extends \Entities\BaseEntityDetails {

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Company", inversedBy="offices")
	 */
	protected $company;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Entities\Location\Location", mappedBy="offices")
	 */
	protected $countries;

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Entities\Medium\Medium")
	 */
	protected $signature;
	
	/**
	 * @var address
	 * @ORM\Column(type="address")
	 */
	protected $address;

	/* ----------------------------- Methods ----------------------------- */


	public function __construct() {
		parent::__construct();

		$this->countries = new \Doctrine\Common\Collections\ArrayCollection;
	}

	/**
	 * @param \Entities\Company\Company
	 * @return \Entities\Company\Office
	 */
	public function setCompany(\Entities\Company\Company $company) {
		$this->company = $company;

		return $this;
	}

	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection of \Entities\Company\Company
	 */
	public function getCompany() {
		return $this->company;
	}

	/**
	 * @param \Entities\Location\Location
	 * @return \Entities\Company\Office
	 */
	public function addCountry(\Entities\Location\Location $country) {
		if(!$this->countries->contains($country)) {
			$this->countries->add($country);
		}
		$country->addOffice($this);

		return $this;
	}

	/**
	 * @param \Entities\Location\Location
	 * @return \Entities\Company\Office
	 */
	public function removeCountry(\Entities\Location\Location $country) {
		if($this->countries->contains($country)) {
			$this->countries->removeElement($country);
		}
		$country->removeOffice($this);

		return $this;
	}

	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection of \Entities\Location\Location
	 */
	public function getCountries() {
		return $this->countries;
	}

	/**
	 * @param \Entities\Medium\Medium
	 * @return \Entities\Company\Office
	 */
	public function setSignature(\Entities\Medium\Medium $signature) {
		$this->signature = $signature;

		return $this;
	}

	/**
	 * @return \Entities\Medium\Medium|NULL
	 */
	public function getSignature() {
		return $this->signature;
	}

	/**
	 * @param \Extras\Types\Address
	 * @return \Entities\Company\Office
	 */
	public function setAddress(\Extras\Types\Address $address) {
		$this->address = $address;

		return $this;
	}

	/**
	 * @return \Extras\Types\Address|NULL
	 */
	public function getAddress() {
		return $this->address;
	}

}