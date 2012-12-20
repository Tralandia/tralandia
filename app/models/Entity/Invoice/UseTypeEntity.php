<?php

namespace Entity\Invoice;

use Entity\Phrase;
use Doctrine\ORM\Mapping as ORM;
use	Extras\Annotation as EA;

/**
 * @ORM\Entity()
 * @ORM\Table(name="invoice_usetype", indexes={@ORM\index(name="slug", columns={"slug"})})
 * @EA\Primary(key="id", value="slug")
 * @EA\Generator(skip="{setSlug}")
 */
class UseType extends \Entity\BaseEntity {

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Entity\Phrase\Phrase", cascade={"persist", "remove"})
	 */
	protected $name;

	/**
	 * @var string
	 * @ORM\Column(type="string")
	 */
	protected $slug;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Package", inversedBy="uses")
	 */
	protected $packages;

	/**
	 * @param string
	 * @return \Entity\Invoice\UseType
	 */
	public function setSlug($slug)
	{
		$this->slug = \Nette\Utils\Strings::webalize($slug);

		return $this;
	}

	//@entity-generator-code --- NEMAZAT !!!

	/* ----------------------------- Methods ----------------------------- */		
	public function __construct()
	{
		parent::__construct();

		$this->packages = new \Doctrine\Common\Collections\ArrayCollection;
	}
		
	/**
	 * @param \Entity\Phrase\Phrase
	 * @return \Entity\Invoice\UseType
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
	 * @return string|NULL
	 */
	public function getSlug()
	{
		return $this->slug;
	}
		
	/**
	 * @param \Entity\Invoice\Package
	 * @return \Entity\Invoice\UseType
	 */
	public function addPackage(\Entity\Invoice\Package $package)
	{
		if(!$this->packages->contains($package)) {
			$this->packages->add($package);
		}

		return $this;
	}
		
	/**
	 * @param \Entity\Invoice\Package
	 * @return \Entity\Invoice\UseType
	 */
	public function removePackage(\Entity\Invoice\Package $package)
	{
		$this->packages->removeElement($package);

		return $this;
	}
		
	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection of \Entity\Invoice\Package
	 */
	public function getPackages()
	{
		return $this->packages;
	}
}