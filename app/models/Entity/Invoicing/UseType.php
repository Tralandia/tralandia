<?php

namespace Entity\Invoicing;

use Entity\Phrase;
use Doctrine\ORM\Mapping as ORM;
use	Extras\Annotation as EA;

/**
 * @ORM\Entity()
 * @ORM\Table(name="invoicing_usetype", indexes={@ORM\index(name="slug", columns={"slug"})})
 * @EA\Primary(key="id", value="slug")
 */
class UseType extends \Entity\BaseEntity {

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Entity\Phrase\Phrase", cascade={"persist", "remove"})
	 */
	protected $name;

	/**
	 * @var slug
	 * @ORM\Column(type="slug")
	 */
	protected $slug;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Package", inversedBy="uses")
	 */
	protected $packages;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Marketing", inversedBy="uses")
	 */
	protected $marketings;

	//@entity-generator-code --- NEMAZAT !!!

	/* ----------------------------- Methods ----------------------------- */		
	public function __construct()
	{
		parent::__construct();

		$this->packages = new \Doctrine\Common\Collections\ArrayCollection;
		$this->marketings = new \Doctrine\Common\Collections\ArrayCollection;
	}
		
	/**
	 * @param \Entity\Phrase\Phrase
	 * @return \Entity\Invoicing\UseType
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
	 * @param slug
	 * @return \Entity\Invoicing\UseType
	 */
	public function setSlug($slug)
	{
		$this->slug = $slug;

		return $this;
	}
		
	/**
	 * @return slug|NULL
	 */
	public function getSlug()
	{
		return $this->slug;
	}
		
	/**
	 * @param \Entity\Invoicing\Package
	 * @return \Entity\Invoicing\UseType
	 */
	public function addPackage(\Entity\Invoicing\Package $package)
	{
		if(!$this->packages->contains($package)) {
			$this->packages->add($package);
		}

		return $this;
	}
		
	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection of \Entity\Invoicing\Package
	 */
	public function getPackages()
	{
		return $this->packages;
	}
		
	/**
	 * @param \Entity\Invoicing\Marketing
	 * @return \Entity\Invoicing\UseType
	 */
	public function addMarketing(\Entity\Invoicing\Marketing $marketing)
	{
		if(!$this->marketings->contains($marketing)) {
			$this->marketings->add($marketing);
		}

		return $this;
	}
		
	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection of \Entity\Invoicing\Marketing
	 */
	public function getMarketings()
	{
		return $this->marketings;
	}
}