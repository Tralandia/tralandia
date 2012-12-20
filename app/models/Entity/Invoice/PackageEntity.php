<?php

namespace Entity\Invoice;

use Entity\Phrase;
use Entity\Location;
use Doctrine\ORM\Mapping as ORM;
use	Extras\Annotation as EA;

/**
 * @ORM\Entity()
 * @ORM\Table(name="invoice_package")
 * @EA\Primary(key="id", value="id")
 */
class Package extends \Entity\BaseEntity {

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Entity\Phrase\Phrase", cascade={"persist", "remove"})
	 */
	protected $name;

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Entity\Phrase\Phrase", cascade={"persist", "remove"})
	 */
	protected $teaser;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="UseType", mappedBy="packages")
	 */
	protected $uses;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entity\Location\Location")
	 */
	protected $primaryLocation;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entity\Currency")
	 */
	protected $currency;

	/**
	 * @var Collection
	 * @ORM\OneToMany(targetEntity="Entity\Invoice\Service", mappedBy="package", cascade={"persist", "remove"})
	 */
	protected $services;

	//@entity-generator-code --- NEMAZAT !!!

	/* ----------------------------- Methods ----------------------------- */		
	public function __construct()
	{
		parent::__construct();

		$this->uses = new \Doctrine\Common\Collections\ArrayCollection;
		$this->services = new \Doctrine\Common\Collections\ArrayCollection;
	}
		
	/**
	 * @param \Entity\Phrase\Phrase
	 * @return \Entity\Invoice\Package
	 */
	public function setName(\Entity\Phrase\Phrase $name)
	{
		$this->name = $name;

		return $this;
	}
		
	/**
	 * @return \Entity\Phrase\Phrase|NULL
	 */
	public function getName()
	{
		return $this->name;
	}
		
	/**
	 * @param \Entity\Phrase\Phrase
	 * @return \Entity\Invoice\Package
	 */
	public function setTeaser(\Entity\Phrase\Phrase $teaser)
	{
		$this->teaser = $teaser;

		return $this;
	}
		
	/**
	 * @return \Entity\Phrase\Phrase|NULL
	 */
	public function getTeaser()
	{
		return $this->teaser;
	}
		
	/**
	 * @param \Entity\Invoice\UseType
	 * @return \Entity\Invoice\Package
	 */
	public function addUse(\Entity\Invoice\UseType $use)
	{
		if(!$this->uses->contains($use)) {
			$this->uses->add($use);
		}
		$use->addPackage($this);

		return $this;
	}
		
	/**
	 * @param \Entity\Invoice\UseType
	 * @return \Entity\Invoice\Package
	 */
	public function removeUse(\Entity\Invoice\UseType $use)
	{
		$this->uses->removeElement($use);
		$use->removePackage($this);

		return $this;
	}
		
	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection of \Entity\Invoice\UseType
	 */
	public function getUses()
	{
		return $this->uses;
	}
		
	/**
	 * @param \Entity\Location\Location
	 * @return \Entity\Invoice\Package
	 */
	public function setPrimaryLocation(\Entity\Location\Location $primaryLocation)
	{
		$this->primaryLocation = $primaryLocation;

		return $this;
	}
		
	/**
	 * @return \Entity\Invoice\Package
	 */
	public function unsetPrimaryLocation()
	{
		$this->primaryLocation = NULL;

		return $this;
	}
		
	/**
	 * @return \Entity\Location\Location|NULL
	 */
	public function getPrimaryLocation()
	{
		return $this->primaryLocation;
	}
		
	/**
	 * @param \Entity\Currency
	 * @return \Entity\Invoice\Package
	 */
	public function setCurrency(\Entity\Currency $currency)
	{
		$this->currency = $currency;

		return $this;
	}
		
	/**
	 * @return \Entity\Invoice\Package
	 */
	public function unsetCurrency()
	{
		$this->currency = NULL;

		return $this;
	}
		
	/**
	 * @return \Entity\Currency|NULL
	 */
	public function getCurrency()
	{
		return $this->currency;
	}
		
	/**
	 * @param \Entity\Invoice\Service
	 * @return \Entity\Invoice\Package
	 */
	public function addService(\Entity\Invoice\Service $service)
	{
		if(!$this->services->contains($service)) {
			$this->services->add($service);
		}
		$service->setPackage($this);

		return $this;
	}
		
	/**
	 * @param \Entity\Invoice\Service
	 * @return \Entity\Invoice\Package
	 */
	public function removeService(\Entity\Invoice\Service $service)
	{
		$this->services->removeElement($service);
		$service->unsetPackage();

		return $this;
	}
		
	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection of \Entity\Invoice\Service
	 */
	public function getServices()
	{
		return $this->services;
	}
}