<?php

namespace Entity\Seo;

use Entity\Attraction;
use Entity\Dictionary;
use Entity\Location;
use Entity\Medium;
use Entity\Rental;
use Doctrine\ORM\Mapping as ORM;
use	Extras\Annotation as EA;

/**
 * @ORM\Entity()
 * @ORM\Table(name="seo_seourl")
 * @EA\Service(name="\Service\Seo\SeoUrl")
 * @EA\ServiceList(name="\Service\Seo\SeoUrlList")
 * @EA\Primary(key="id", value="id")
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
	 * @ORM\Column(type="integer")
	 */
	protected $page;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entity\Rental\Amenity")
	 */
	protected $tag;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entity\Attraction\Attraction")
	 */
	protected $attractionType;

	/**
	 * @var Collection
	 * @ORM\OneToMany(targetEntity="Entity\Medium\Medium", mappedBy="seoUrl")
	 */
	protected $media;

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Entity\Dictionary\Phrase", cascade={"persist", "remove"})
	 */
	protected $title;

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Entity\Dictionary\Phrase", cascade={"persist", "remove"})
	 */
	protected $heading;

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Entity\Dictionary\Phrase", cascade={"persist", "remove"})
	 */
	protected $tabName;

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Entity\Dictionary\Phrase", cascade={"persist", "remove"})
	 */
	protected $description;

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Entity\Dictionary\Phrase", cascade={"persist", "remove"})
	 */
	protected $ppcKeywords;

	



















//@entity-generator-code <--- NEMAZAT !!!

	/* ----------------------------- Methods ----------------------------- */		
	public function __construct() {
		parent::__construct();

		$this->media = new \Doctrine\Common\Collections\ArrayCollection;
	}
		
	/**
	 * @param \Entity\Location\Location
	 * @return \Entity\Seo\SeoUrl
	 */
	public function setCountry(\Entity\Location\Location $country) {
		$this->country = $country;

		return $this;
	}
		
	/**
	 * @return \Entity\Seo\SeoUrl
	 */
	public function unsetCountry() {
		$this->country = NULL;

		return $this;
	}
		
	/**
	 * @return \Entity\Location\Location|NULL
	 */
	public function getCountry() {
		return $this->country;
	}
		
	/**
	 * @param \Entity\Rental\Type
	 * @return \Entity\Seo\SeoUrl
	 */
	public function setRentalType(\Entity\Rental\Type $rentalType) {
		$this->rentalType = $rentalType;

		return $this;
	}
		
	/**
	 * @return \Entity\Seo\SeoUrl
	 */
	public function unsetRentalType() {
		$this->rentalType = NULL;

		return $this;
	}
		
	/**
	 * @return \Entity\Rental\Type|NULL
	 */
	public function getRentalType() {
		return $this->rentalType;
	}
		
	/**
	 * @param \Entity\Location\Location
	 * @return \Entity\Seo\SeoUrl
	 */
	public function setLocation(\Entity\Location\Location $location) {
		$this->location = $location;

		return $this;
	}
		
	/**
	 * @return \Entity\Seo\SeoUrl
	 */
	public function unsetLocation() {
		$this->location = NULL;

		return $this;
	}
		
	/**
	 * @return \Entity\Location\Location|NULL
	 */
	public function getLocation() {
		return $this->location;
	}
		
	/**
	 * @param integer
	 * @return \Entity\Seo\SeoUrl
	 */
	public function setPage($page) {
		$this->page = $page;

		return $this;
	}
		
	/**
	 * @return integer|NULL
	 */
	public function getPage() {
		return $this->page;
	}
		
	/**
	 * @param \Entity\Rental\Amenity
	 * @return \Entity\Seo\SeoUrl
	 */
	public function setTag(\Entity\Rental\Amenity $tag) {
		$this->tag = $tag;

		return $this;
	}
		
	/**
	 * @return \Entity\Seo\SeoUrl
	 */
	public function unsetTag() {
		$this->tag = NULL;

		return $this;
	}
		
	/**
	 * @return \Entity\Rental\Amenity|NULL
	 */
	public function getTag() {
		return $this->tag;
	}
		
	/**
	 * @param \Entity\Attraction\Attraction
	 * @return \Entity\Seo\SeoUrl
	 */
	public function setAttractionType(\Entity\Attraction\Attraction $attractionType) {
		$this->attractionType = $attractionType;

		return $this;
	}
		
	/**
	 * @return \Entity\Seo\SeoUrl
	 */
	public function unsetAttractionType() {
		$this->attractionType = NULL;

		return $this;
	}
		
	/**
	 * @return \Entity\Attraction\Attraction|NULL
	 */
	public function getAttractionType() {
		return $this->attractionType;
	}
		
	/**
	 * @param \Entity\Medium\Medium
	 * @return \Entity\Seo\SeoUrl
	 */
	public function addMedium(\Entity\Medium\Medium $medium) {
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
	public function removeMedium(\Entity\Medium\Medium $medium) {
		if($this->media->contains($medium)) {
			$this->media->removeElement($medium);
		}
		$medium->unsetSeoUrl();

		return $this;
	}
		
	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection of \Entity\Medium\Medium
	 */
	public function getMedia() {
		return $this->media;
	}
		
	/**
	 * @param \Entity\Dictionary\Phrase
	 * @return \Entity\Seo\SeoUrl
	 */
	public function setTitle(\Entity\Dictionary\Phrase $title) {
		$this->title = $title;

		return $this;
	}
		
	/**
	 * @return \Entity\Dictionary\Phrase|NULL
	 */
	public function getTitle() {
		return $this->title;
	}
		
	/**
	 * @param \Entity\Dictionary\Phrase
	 * @return \Entity\Seo\SeoUrl
	 */
	public function setH1(\Entity\Dictionary\Phrase $h1) {
		$this->h1 = $h1;

		return $this;
	}
		
	/**
	 * @return \Entity\Dictionary\Phrase|NULL
	 */
	public function getH1() {
		return $this->h1;
	}
		
	/**
	 * @param \Entity\Dictionary\Phrase
	 * @return \Entity\Seo\SeoUrl
	 */
	public function setTabName(\Entity\Dictionary\Phrase $tabName) {
		$this->tabName = $tabName;

		return $this;
	}
		
	/**
	 * @return \Entity\Dictionary\Phrase|NULL
	 */
	public function getTabName() {
		return $this->tabName;
	}
		
	/**
	 * @param \Entity\Dictionary\Phrase
	 * @return \Entity\Seo\SeoUrl
	 */
	public function setDescription(\Entity\Dictionary\Phrase $description) {
		$this->description = $description;

		return $this;
	}
		
	/**
	 * @return \Entity\Dictionary\Phrase|NULL
	 */
	public function getDescription() {
		return $this->description;
	}
		
	/**
	 * @param \Entity\Dictionary\Phrase
	 * @return \Entity\Seo\SeoUrl
	 */
	public function setPpcKeyword(\Entity\Dictionary\Phrase $ppcKeyword) {
		$this->ppcKeywords = $ppcKeyword;

		return $this;
	}
		
	/**
	 * @return \Entity\Dictionary\Phrase|NULL
	 */
	public function getPpcKeywords() {
		return $this->ppcKeywords;
	}
}