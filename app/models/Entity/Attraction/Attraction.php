<?php

namespace Entity\Attraction;

use Entity\Dictionary;
use Entity\Location;
use Entity\Medium;
use Entity\User;
use Doctrine\ORM\Mapping as ORM;
use	Extras\Annotation as EA;

/**
 * @ORM\Entity()
 * @ORM\Table(name="attraction_attraction")
 * @EA\Service(name="\Service\Attraction\Attraction")
 * @EA\ServiceList(name="\Service\Attraction\AttractionList")
 * @EA\Primary(key="id", value="id")
 */
class Attraction extends \Entity\BaseEntityDetails {

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Type")
	 */
	protected $type;

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Entity\Dictionary\Phrase", cascade={"persist", "remove"})
	 */
	protected $name;

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Entity\Dictionary\Phrase", cascade={"persist", "remove"})
	 */
	protected $description;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entity\Location\Location")
	 */
	protected $country;

	/**
	 * @var latlong
	 * @ORM\Column(type="latlong", nullable=true)
	 */
	protected $latitude;

	/**
	 * @var latlong
	 * @ORM\Column(type="latlong", nullable=true)
	 */
	protected $longitude;

	/**
	 * @var contacts
	 * @ORM\Column(type="contacts", nullable=true)
	 */
	protected $contacts;

	/**
	 * @var Collection
	 * @ORM\OneToMany(targetEntity="Entity\Medium\Medium", mappedBy="attraction", cascade={"persist", "remove"})
	 */
	protected $media;


//@entity-generator-code <--- NEMAZAT !!!

	/* ----------------------------- Methods ----------------------------- */		
	public function __construct() {
		parent::__construct();

		$this->media = new \Doctrine\Common\Collections\ArrayCollection;
	}
		
	/**
	 * @param \Entity\Attraction\Type
	 * @return \Entity\Attraction\Attraction
	 */
	public function setType(\Entity\Attraction\Type $type) {
		$this->type = $type;

		return $this;
	}
		
	/**
	 * @return \Entity\Attraction\Attraction
	 */
	public function unsetType() {
		$this->type = NULL;

		return $this;
	}
		
	/**
	 * @return \Entity\Attraction\Type|NULL
	 */
	public function getType() {
		return $this->type;
	}
		
	/**
	 * @param \Entity\Dictionary\Phrase
	 * @return \Entity\Attraction\Attraction
	 */
	public function setName(\Entity\Dictionary\Phrase $name) {
		$this->name = $name;

		return $this;
	}
		
	/**
	 * @return \Entity\Dictionary\Phrase|NULL
	 */
	public function getName() {
		return $this->name;
	}
		
	/**
	 * @param \Entity\Dictionary\Phrase
	 * @return \Entity\Attraction\Attraction
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
	 * @param \Entity\Location\Location
	 * @return \Entity\Attraction\Attraction
	 */
	public function setCountry(\Entity\Location\Location $country) {
		$this->country = $country;

		return $this;
	}
		
	/**
	 * @return \Entity\Attraction\Attraction
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
	 * @param \Extras\Types\Latlong
	 * @return \Entity\Attraction\Attraction
	 */
	public function setLatitude(\Extras\Types\Latlong $latitude) {
		$this->latitude = $latitude;

		return $this;
	}
		
	/**
	 * @return \Extras\Types\Latlong|NULL
	 */
	public function getLatitude() {
		return $this->latitude;
	}
		
	/**
	 * @param \Extras\Types\Latlong
	 * @return \Entity\Attraction\Attraction
	 */
	public function setLongitude(\Extras\Types\Latlong $longitude) {
		$this->longitude = $longitude;

		return $this;
	}
		
	/**
	 * @return \Extras\Types\Latlong|NULL
	 */
	public function getLongitude() {
		return $this->longitude;
	}
		
	/**
	 * @param \Extras\Types\Contacts
	 * @return \Entity\Attraction\Attraction
	 */
	public function setContacts(\Extras\Types\Contacts $contacts) {
		$this->contacts = $contacts;

		return $this;
	}
		
	/**
	 * @return \Extras\Types\Contacts|NULL
	 */
	public function getContacts() {
		return $this->contacts;
	}
		
	/**
	 * @param \Entity\Medium\Medium
	 * @return \Entity\Attraction\Attraction
	 */
	public function addMedium(\Entity\Medium\Medium $medium) {
		if(!$this->media->contains($medium)) {
			$this->media->add($medium);
		}
		$medium->setAttraction($this);

		return $this;
	}
		
	/**
	 * @param \Entity\Medium\Medium
	 * @return \Entity\Attraction\Attraction
	 */
	public function removeMedium(\Entity\Medium\Medium $medium) {
		if($this->media->contains($medium)) {
			$this->media->removeElement($medium);
		}
		$medium->unsetAttraction();

		return $this;
	}
		
	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection of \Entity\Medium\Medium
	 */
	public function getMedia() {
		return $this->media;
	}
}