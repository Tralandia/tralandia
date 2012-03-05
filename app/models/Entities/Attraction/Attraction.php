<?php

namespace Entities\Attraction;

use Entities\Attraction;
use Entities\Contact;
use Entities\Dictionary;
use Entities\Location;
use Entities\Medium;
use Entities\User;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="attraction_attraction")
 */
class Attraction extends \BaseEntityDetails {

	/**
	 * @var Collection
	 * @ORM\ManyToMany(type="Type")
	 */
	protected $type;

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Dictionary\Phrase")
	 */
	protected $name;

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Dictionary\Phrase")
	 */
	protected $description;

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Location\Location")
	 */
	protected $country;

	/**
	 * @var latlong
	 * @ORM\ManyToMany(type="latlong")
	 */
	protected $latitude;

	/**
	 * @var latlong
	 * @ORM\ManyToMany(type="latlong")
	 */
	protected $longitude;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Contact\Contact")
	 */
	protected $contacts;

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="User\User")
	 */
	protected $managingUser;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Medium\Medium")
	 */
	protected $media;


	public function __construct() {
		parent::__construct();
		$this->contacts = new ArrayCollection();
		$this->media = new ArrayCollection();
	}


	/**
	 * @param Type $type
	 * @return Attraction
	 */
	public function setType(Type  $type) {
		$this->type = $type;
		return $this;
	}


	/**
	 * @return Type
	 */
	public function getType() {
		return $this->type;
	}


	/**
	 * @param Dictionary\Phrase $name
	 * @return Attraction
	 */
	public function setName(Dictionary\Phrase  $name) {
		$this->name = $name;
		return $this;
	}


	/**
	 * @return Dictionary\Phrase
	 */
	public function getName() {
		return $this->name;
	}


	/**
	 * @param Dictionary\Phrase $description
	 * @return Attraction
	 */
	public function setDescription(Dictionary\Phrase  $description) {
		$this->description = $description;
		return $this;
	}


	/**
	 * @return Dictionary\Phrase
	 */
	public function getDescription() {
		return $this->description;
	}


	/**
	 * @param Location\Location $country
	 * @return Attraction
	 */
	public function setCountry(Location\Location  $country) {
		$this->country = $country;
		return $this;
	}


	/**
	 * @return Location\Location
	 */
	public function getCountry() {
		return $this->country;
	}


	/**
	 * @param latlong $latitude
	 * @return Attraction
	 */
	public function setLatitude($latitude) {
		$this->latitude = $latitude;
		return $this;
	}


	/**
	 * @return latlong
	 */
	public function getLatitude() {
		return $this->latitude;
	}


	/**
	 * @param latlong $longitude
	 * @return Attraction
	 */
	public function setLongitude($longitude) {
		$this->longitude = $longitude;
		return $this;
	}


	/**
	 * @return latlong
	 */
	public function getLongitude() {
		return $this->longitude;
	}


	/**
	 * @param Contact\Contact $contact
	 * @return Attraction
	 */
	public function addContact(Contact\Contact  $contact) {
		$this->contacts->add($contact);
		return $this;
	}


	/**
	 * @param Contact\Contact $contact
	 * @return Attraction
	 */
	public function removeContact(Contact\Contact  $contact) {
		$this->contacts->removeElement($contact);
		return $this;
	}


	/**
	 * @return Contact\Contact[]
	 */
	public function getContact() {
		return $this->contacts->toArray();
	}


	/**
	 * @param User\User $managingUser
	 * @return Attraction
	 */
	public function setManagingUser(User\User  $managingUser) {
		$this->managingUser = $managingUser;
		return $this;
	}


	/**
	 * @return User\User
	 */
	public function getManagingUser() {
		return $this->managingUser;
	}


	/**
	 * @param Medium\Medium $medium
	 * @return Attraction
	 */
	public function addMedium(Medium\Medium  $medium) {
		$this->media->add($medium);
		return $this;
	}


	/**
	 * @param Medium\Medium $medium
	 * @return Attraction
	 */
	public function removeMedium(Medium\Medium  $medium) {
		$this->media->removeElement($medium);
		return $this;
	}


	/**
	 * @return Medium\Medium[]
	 */
	public function getMedium() {
		return $this->media->toArray();
	}

}
