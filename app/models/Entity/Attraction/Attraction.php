<?php

namespace Entity\Attraction;

use Entity\Contact;
use Entity\Dictionary;
use Entity\Location;
use Entity\Medium;
use Entity\User;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="attraction_attraction")
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
	 * @ORM\Column(type="latlong")
	 */
	protected $latitude;

	/**
	 * @var latlong
	 * @ORM\Column(type="latlong")
	 */
	protected $longitude;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Entity\Contact\Contact", mappedBy="attractions")
	 */
	protected $contacts;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entity\User\User")
	 */
	protected $managingUser;

	/**
	 * @var Collection
	 * @ORM\OneToMany(targetEntity="Entity\Medium\Medium", mappedBy="attraction")
	 */
	protected $media;

	






//@entity-generator-code <--- NEMAZAT !!!

	/* ----------------------------- Methods ----------------------------- */		
	public function __construct() {
		parent::__construct();

		$this->contacts = new \Doctrine\Common\Collections\ArrayCollection;
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
	 * @param \Entity\Contact\Contact
	 * @return \Entity\Attraction\Attraction
	 */
	public function addContact(\Entity\Contact\Contact $contact) {
		if(!$this->contacts->contains($contact)) {
			$this->contacts->add($contact);
		}
		$contact->addAttraction($this);

		return $this;
	}
		
	/**
	 * @param \Entity\Contact\Contact
	 * @return \Entity\Attraction\Attraction
	 */
	public function removeContact(\Entity\Contact\Contact $contact) {
		if($this->contacts->contains($contact)) {
			$this->contacts->removeElement($contact);
		}
		$contact->removeAttraction($this);

		return $this;
	}
		
	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection of \Entity\Contact\Contact
	 */
	public function getContacts() {
		return $this->contacts;
	}
		
	/**
	 * @param \Entity\User\User
	 * @return \Entity\Attraction\Attraction
	 */
	public function setManagingUser(\Entity\User\User $managingUser) {
		$this->managingUser = $managingUser;

		return $this;
	}
		
	/**
	 * @return \Entity\Attraction\Attraction
	 */
	public function unsetManagingUser() {
		$this->managingUser = NULL;

		return $this;
	}
		
	/**
	 * @return \Entity\User\User|NULL
	 */
	public function getManagingUser() {
		return $this->managingUser;
	}
		
	/**
	 * @param \Entity\Medium\Medium
	 * @return \Entity\Attraction\Attraction
	 */
	public function addMedia(\Entity\Medium\Medium $media) {
		if(!$this->media->contains($media)) {
			$this->media->add($media);
		}
		$media->setAttraction($this);

		return $this;
	}
		
	/**
	 * @param \Entity\Medium\Medium
	 * @return \Entity\Attraction\Attraction
	 */
	public function removeMedia(\Entity\Medium\Medium $media) {
		if($this->media->contains($media)) {
			$this->media->removeElement($media);
		}
		$media->unsetAttraction();

		return $this;
	}
		
	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection of \Entity\Medium\Medium
	 */
	public function getMedia() {
		return $this->media;
	}
}