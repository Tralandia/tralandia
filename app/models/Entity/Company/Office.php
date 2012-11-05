<?php

namespace Entity\Company;

use Doctrine\ORM\Mapping as ORM;
use	Extras\Annotation as EA;

/**
 * @ORM\Entity()
 * @ORM\Table(name="company_office")
 * @EA\Primary(key="id", value="address")
 */
class Office extends \Entity\BaseEntityDetails {

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $name;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Company", inversedBy="offices")
	 */
	protected $company;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Entity\Location\Location", mappedBy="offices")
	 */
	protected $countries;

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Entity\Medium\Medium", cascade={"persist", "remove"})
	 * @ORM\JoinColumn(onDelete="CASCADE")
	 */
	protected $signature;
	
	/**
	 * @var address
	 * @ORM\Column(type="address")
	 */
	protected $address;

								//@entity-generator-code --- NEMAZAT !!!

	/* ----------------------------- Methods ----------------------------- */		
	public function __construct()
	{
		parent::__construct();

		$this->countries = new \Doctrine\Common\Collections\ArrayCollection;
	}
		
	/**
	 * @param string
	 * @return \Entity\Company\Office
	 */
	public function setName($name)
	{
		$this->name = $name;

		return $this;
	}
		
	/**
	 * @return \Entity\Company\Office
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
	 * @param \Entity\Company\Company
	 * @return \Entity\Company\Office
	 */
	public function setCompany(\Entity\Company\Company $company)
	{
		$this->company = $company;

		return $this;
	}
		
	/**
	 * @return \Entity\Company\Office
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
	 * @param \Entity\Location\Location
	 * @return \Entity\Company\Office
	 */
	public function addCountry(\Entity\Location\Location $country)
	{
		if(!$this->countries->contains($country)) {
			$this->countries->add($country);
		}
		$country->addOffice($this);

		return $this;
	}
		
	/**
	 * @param \Entity\Location\Location
	 * @return \Entity\Company\Office
	 */
	public function removeCountry(\Entity\Location\Location $country)
	{
		if($this->countries->contains($country)) {
			$this->countries->removeElement($country);
		}
		$country->removeOffice($this);

		return $this;
	}
		
	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection of \Entity\Location\Location
	 */
	public function getCountries()
	{
		return $this->countries;
	}
		
	/**
	 * @param \Entity\Medium\Medium
	 * @return \Entity\Company\Office
	 */
	public function setSignature(\Entity\Medium\Medium $signature)
	{
		$this->signature = $signature;

		return $this;
	}
		
	/**
	 * @return \Entity\Medium\Medium|NULL
	 */
	public function getSignature()
	{
		return $this->signature;
	}
		
	/**
	 * @param \Extras\Types\Address
	 * @return \Entity\Company\Office
	 */
	public function setAddress(\Extras\Types\Address $address)
	{
		$this->address = $address;

		return $this;
	}
		
	/**
	 * @return \Extras\Types\Address|NULL
	 */
	public function getAddress()
	{
		return $this->address;
	}
}