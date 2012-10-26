<?php

namespace Entity\Invoice;

use Entity\Phrase;
use Entity\Location;
use Doctrine\ORM\Mapping as ORM;
use	Extras\Annotation as EA;

/**
 * @ORM\Entity()
 * @ORM\Table(name="invoice_marketing", indexes={@ORM\index(name="countTotal", columns={"countTotal"}), @ORM\index(name="countLeft", columns={"countLeft"}), @ORM\index(name="validFrom", columns={"validFrom"}), @ORM\index(name="validTo", columns={"validTo"})})
 * @EA\Primary(key="id", value="id")
 */
class Marketing extends \Entity\BaseEntity {

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Entity\Phrase\Phrase", cascade={"persist", "remove"})
	 */
	protected $name;

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Entity\Phrase\Phrase", cascade={"persist", "remove"})
	 */
	protected $description;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Package")
	 */
	protected $package;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Entity\Location\Location", mappedBy="marketings")
	 */
	protected $locations;

	/**
	 * @var integer
	 * @ORM\Column(type="integer")
	 */
	protected $countTotal;

	/**
	 * @var integer
	 * @ORM\Column(type="integer")
	 */
	protected $countLeft;

	/**
	 * @var datetime
	 * @ORM\Column(type="datetime")
	 */
	protected $validFrom;

	/**
	 * @var datetime
	 * @ORM\Column(type="datetime")
	 */
	protected $validTo;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="UseType", mappedBy="marketings")
	 */
	protected $uses;

	




















									//@entity-generator-code --- NEMAZAT !!!

	/* ----------------------------- Methods ----------------------------- */		
	public function __construct()
	{
		parent::__construct();

		$this->locations = new \Doctrine\Common\Collections\ArrayCollection;
		$this->uses = new \Doctrine\Common\Collections\ArrayCollection;
	}
		
	/**
	 * @param \Entity\Phrase\Phrase
	 * @return \Entity\Invoice\Marketing
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
	 * @return \Entity\Invoice\Marketing
	 */
	public function setDescription(\Entity\Phrase\Phrase $description)
	{
		$this->description = $description;

		return $this;
	}
		
	/**
	 * @return \Entity\Phrase\Phrase|NULL
	 */
	public function getDescription()
	{
		return $this->description;
	}
		
	/**
	 * @param \Entity\Invoice\Package
	 * @return \Entity\Invoice\Marketing
	 */
	public function setPackage(\Entity\Invoice\Package $package)
	{
		$this->package = $package;

		return $this;
	}
		
	/**
	 * @return \Entity\Invoice\Marketing
	 */
	public function unsetPackage()
	{
		$this->package = NULL;

		return $this;
	}
		
	/**
	 * @return \Entity\Invoice\Package|NULL
	 */
	public function getPackage()
	{
		return $this->package;
	}
		
	/**
	 * @param \Entity\Location\Location
	 * @return \Entity\Invoice\Marketing
	 */
	public function addLocation(\Entity\Location\Location $location)
	{
		if(!$this->locations->contains($location)) {
			$this->locations->add($location);
		}
		$location->addMarketing($this);

		return $this;
	}
		
	/**
	 * @param \Entity\Location\Location
	 * @return \Entity\Invoice\Marketing
	 */
	public function removeLocation(\Entity\Location\Location $location)
	{
		if($this->locations->contains($location)) {
			$this->locations->removeElement($location);
		}
		$location->removeMarketing($this);

		return $this;
	}
		
	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection of \Entity\Location\Location
	 */
	public function getLocations()
	{
		return $this->locations;
	}
		
	/**
	 * @param integer
	 * @return \Entity\Invoice\Marketing
	 */
	public function setCountTotal($countTotal)
	{
		$this->countTotal = $countTotal;

		return $this;
	}
		
	/**
	 * @return integer|NULL
	 */
	public function getCountTotal()
	{
		return $this->countTotal;
	}
		
	/**
	 * @param integer
	 * @return \Entity\Invoice\Marketing
	 */
	public function setCountLeft($countLeft)
	{
		$this->countLeft = $countLeft;

		return $this;
	}
		
	/**
	 * @return integer|NULL
	 */
	public function getCountLeft()
	{
		return $this->countLeft;
	}
		
	/**
	 * @param \DateTime
	 * @return \Entity\Invoice\Marketing
	 */
	public function setValidFrom(\DateTime $validFrom)
	{
		$this->validFrom = $validFrom;

		return $this;
	}
		
	/**
	 * @return \DateTime|NULL
	 */
	public function getValidFrom()
	{
		return $this->validFrom;
	}
		
	/**
	 * @param \DateTime
	 * @return \Entity\Invoice\Marketing
	 */
	public function setValidTo(\DateTime $validTo)
	{
		$this->validTo = $validTo;

		return $this;
	}
		
	/**
	 * @return \DateTime|NULL
	 */
	public function getValidTo()
	{
		return $this->validTo;
	}
		
	/**
	 * @param \Entity\Invoice\UseType
	 * @return \Entity\Invoice\Marketing
	 */
	public function addUse(\Entity\Invoice\UseType $use)
	{
		if(!$this->uses->contains($use)) {
			$this->uses->add($use);
		}
		$use->addMarketing($this);

		return $this;
	}
		
	/**
	 * @param \Entity\Invoice\UseType
	 * @return \Entity\Invoice\Marketing
	 */
	public function removeUse(\Entity\Invoice\UseType $use)
	{
		if($this->uses->contains($use)) {
			$this->uses->removeElement($use);
		}
		$use->removeMarketing($this);

		return $this;
	}
		
	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection of \Entity\Invoice\UseType
	 */
	public function getUses()
	{
		return $this->uses;
	}
}