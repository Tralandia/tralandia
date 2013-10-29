<?php

namespace Entity\Seo;

use Entity\Phrase;
use Entity\Location;
use Entity\Medium;
use Entity\Rental;
use Doctrine\ORM\Mapping as ORM;
use	Extras\Annotation as EA;

/**
 * @ORM\Entity()
 * @ORM\Table(name="seo_seourl")
 *
 */
class SeoUrl extends \Entity\BaseEntity {

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entity\Location\Location")
	 */
	protected $location;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entity\Rental\Type")
	 */
	protected $rentalType;

	/**
	 * @var integer
	 * @ORM\Column(type="integer", nullable=true)
	 */
	protected $page;

	/**
	 * @var Collection
	 * @ORM\OneToMany(targetEntity="Entity\Medium\Medium", mappedBy="seoUrl", cascade={"persist", "remove"})
	 */
	protected $media;

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Entity\Phrase\Phrase", cascade={"persist", "remove"})
	 */
	protected $title;

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Entity\Phrase\Phrase", cascade={"persist", "remove"})
	 */
	protected $heading;

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Entity\Phrase\Phrase", cascade={"persist", "remove"})
	 */
	protected $tabName;

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Entity\Phrase\Phrase", cascade={"persist", "remove"})
	 */
	protected $description;

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Entity\Phrase\Phrase", cascade={"persist", "remove"})
	 */
	protected $ppcKeywords;


	//@entity-generator-code --- NEMAZAT !!!

	/* ----------------------------- Methods ----------------------------- */
	public function __construct()
	{
		parent::__construct();

		$this->media = new \Doctrine\Common\Collections\ArrayCollection;
	}

	/**
	 * @param \Entity\Location\Location
	 * @return \Entity\Seo\SeoUrl
	 */
	public function setLocation(\Entity\Location\Location $location)
	{
		$this->location = $location;

		return $this;
	}

	/**
	 * @return \Entity\Seo\SeoUrl
	 */
	public function unsetLocation()
	{
		$this->location = NULL;

		return $this;
	}

	/**
	 * @return \Entity\Location\Location|NULL
	 */
	public function getLocation()
	{
		return $this->location;
	}

	/**
	 * @param \Entity\Rental\Type
	 * @return \Entity\Seo\SeoUrl
	 */
	public function setRentalType(\Entity\Rental\Type $rentalType)
	{
		$this->rentalType = $rentalType;

		return $this;
	}

	/**
	 * @return \Entity\Seo\SeoUrl
	 */
	public function unsetRentalType()
	{
		$this->rentalType = NULL;

		return $this;
	}

	/**
	 * @return \Entity\Rental\Type|NULL
	 */
	public function getRentalType()
	{
		return $this->rentalType;
	}

	/**
	 * @param integer
	 * @return \Entity\Seo\SeoUrl
	 */
	public function setPage($page)
	{
		$this->page = $page;

		return $this;
	}

	/**
	 * @return \Entity\Seo\SeoUrl
	 */
	public function unsetPage()
	{
		$this->page = NULL;

		return $this;
	}

	/**
	 * @return integer|NULL
	 */
	public function getPage()
	{
		return $this->page;
	}

	/**
	 * @param \Entity\Medium\Medium
	 * @return \Entity\Seo\SeoUrl
	 */
	public function addMedium(\Entity\Medium\Medium $medium)
	{
		if(!$this->media->contains($medium)) {
			$this->media->add($medium);
		}
		$medium->setSeoUrl($this);

		return $this;
	}

	/**
	 * @param \Entity\Medium\Medium
	 * @return \Entity\Seo\SeoUrl
	 */
	public function removeMedium(\Entity\Medium\Medium $medium)
	{
		$this->media->removeElement($medium);
		$medium->unsetSeoUrl();

		return $this;
	}

	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection|\Entity\Medium\Medium[]
	 */
	public function getMedia()
	{
		return $this->media;
	}

	/**
	 * @param \Entity\Phrase\Phrase
	 * @return \Entity\Seo\SeoUrl
	 */
	public function setTitle(\Entity\Phrase\Phrase $title)
	{
		$this->title = $title;

		return $this;
	}

	/**
	 * @return \Entity\Phrase\Phrase|NULL
	 */
	public function getTitle()
	{
		return $this->title;
	}

	/**
	 * @param \Entity\Phrase\Phrase
	 * @return \Entity\Seo\SeoUrl
	 */
	public function setHeading(\Entity\Phrase\Phrase $heading)
	{
		$this->heading = $heading;

		return $this;
	}

	/**
	 * @return \Entity\Phrase\Phrase|NULL
	 */
	public function getHeading()
	{
		return $this->heading;
	}

	/**
	 * @param \Entity\Phrase\Phrase
	 * @return \Entity\Seo\SeoUrl
	 */
	public function setTabName(\Entity\Phrase\Phrase $tabName)
	{
		$this->tabName = $tabName;

		return $this;
	}

	/**
	 * @return \Entity\Phrase\Phrase|NULL
	 */
	public function getTabName()
	{
		return $this->tabName;
	}

	/**
	 * @param \Entity\Phrase\Phrase
	 * @return \Entity\Seo\SeoUrl
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
	 * @param \Entity\Phrase\Phrase
	 * @return \Entity\Seo\SeoUrl
	 */
	public function setPpcKeyword(\Entity\Phrase\Phrase $ppcKeyword)
	{
		$this->ppcKeywords = $ppcKeywords;

		return $this;
	}

	/**
	 * @return \Entity\Phrase\Phrase|NULL
	 */
	public function getPpcKeywords()
	{
		return $this->ppcKeywords;
	}
}
