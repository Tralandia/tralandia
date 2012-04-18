<?php

namespace Entity\Contact;

use Entity\Attraction;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="contact_contact")
 */
class Contact extends \Entity\BaseEntity {

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Type", cascade={"persist"})
	 */
	protected $type;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $value;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Entity\Attraction\Attraction", inversedBy="contacts", cascade={"persist"})
	 */
	protected $attractions;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Entity\Rental\Rental", inversedBy="contacts", cascade={"persist"})
	 */
	protected $rentals;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entity\User\User", inversedBy="contacts", cascade={"persist"})
	 */
	protected $user;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Entity\Location\Location", inversedBy="contacts", cascade={"persist"})
	 */
	protected $locations;

	/**
	 * @var json
	 * @ORM\Column(type="json", nullable=true)
	 */
	protected $info;

	/**
	 * @var boolean
	 * @ORM\Column(type="boolean")
	 */
	protected $subscribed = TRUE;

	/**
	 * @var boolean
	 * @ORM\Column(type="boolean")
	 */
	protected $banned = FALSE;

	/**
	 * @var boolean
	 * @ORM\Column(type="boolean")
	 */
	protected $full = FALSE;

	/**
	 * @var boolean
	 * @ORM\Column(type="boolean")
	 */
	protected $spam = FALSE;

	





//@entity-generator-code <--- NEMAZAT !!!

	/* ----------------------------- Methods ----------------------------- */		
	public function __construct() {
		parent::__construct();

		$this->attractions = new \Doctrine\Common\Collections\ArrayCollection;
		$this->rentals = new \Doctrine\Common\Collections\ArrayCollection;
		$this->locations = new \Doctrine\Common\Collections\ArrayCollection;
	}
		
	/**
	 * @param \Entity\Contact\Type
	 * @return \Entity\Contact\Contact
	 */
	public function setType(\Entity\Contact\Type $type) {
		$this->type = $type;

		return $this;
	}
		
	/**
	 * @return \Entity\Contact\Contact
	 */
	public function unsetType() {
		$this->type = NULL;

		return $this;
	}
		
	/**
	 * @return \Entity\Contact\Type|NULL
	 */
	public function getType() {
		return $this->type;
	}
		
	/**
	 * @param string
	 * @return \Entity\Contact\Contact
	 */
	public function setValue($value) {
		$this->value = $value;

		return $this;
	}
		
	/**
	 * @return \Entity\Contact\Contact
	 */
	public function unsetValue() {
		$this->value = NULL;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getValue() {
		return $this->value;
	}
		
	/**
	 * @param \Entity\Attraction\Attraction
	 * @return \Entity\Contact\Contact
	 */
	public function addAttraction(\Entity\Attraction\Attraction $attraction) {
		if(!$this->attractions->contains($attraction)) {
			$this->attractions->add($attraction);
		}

		return $this;
	}
		
	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection of \Entity\Attraction\Attraction
	 */
	public function getAttractions() {
		return $this->attractions;
	}
		
	/**
	 * @param \Entity\Rental\Rental
	 * @return \Entity\Contact\Contact
	 */
	public function addRental(\Entity\Rental\Rental $rental) {
		if(!$this->rentals->contains($rental)) {
			$this->rentals->add($rental);
		}

		return $this;
	}
		
	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection of \Entity\Rental\Rental
	 */
	public function getRentals() {
		return $this->rentals;
	}
		
	/**
	 * @param \Entity\User\User
	 * @return \Entity\Contact\Contact
	 */
	public function setUser(\Entity\User\User $user) {
		$this->user = $user;

		return $this;
	}
		
	/**
	 * @return \Entity\Contact\Contact
	 */
	public function unsetUser() {
		$this->user = NULL;

		return $this;
	}
		
	/**
	 * @return \Entity\User\User|NULL
	 */
	public function getUser() {
		return $this->user;
	}
		
	/**
	 * @param \Entity\Location\Location
	 * @return \Entity\Contact\Contact
	 */
	public function addLocation(\Entity\Location\Location $location) {
		if(!$this->locations->contains($location)) {
			$this->locations->add($location);
		}

		return $this;
	}
		
	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection of \Entity\Location\Location
	 */
	public function getLocations() {
		return $this->locations;
	}
		
	/**
	 * @param json
	 * @return \Entity\Contact\Contact
	 */
	public function setInfo($info) {
		$this->info = $info;

		return $this;
	}
		
	/**
	 * @return \Entity\Contact\Contact
	 */
	public function unsetInfo() {
		$this->info = NULL;

		return $this;
	}
		
	/**
	 * @return json|NULL
	 */
	public function getInfo() {
		return $this->info;
	}
		
	/**
	 * @param boolean
	 * @return \Entity\Contact\Contact
	 */
	public function setSubscribed($subscribed) {
		$this->subscribed = $subscribed;

		return $this;
	}
		
	/**
	 * @return boolean|NULL
	 */
	public function getSubscribed() {
		return $this->subscribed;
	}
		
	/**
	 * @param boolean
	 * @return \Entity\Contact\Contact
	 */
	public function setBanned($banned) {
		$this->banned = $banned;

		return $this;
	}
		
	/**
	 * @return boolean|NULL
	 */
	public function getBanned() {
		return $this->banned;
	}
		
	/**
	 * @param boolean
	 * @return \Entity\Contact\Contact
	 */
	public function setFull($full) {
		$this->full = $full;

		return $this;
	}
		
	/**
	 * @return boolean|NULL
	 */
	public function getFull() {
		return $this->full;
	}
		
	/**
	 * @param boolean
	 * @return \Entity\Contact\Contact
	 */
	public function setSpam($spam) {
		$this->spam = $spam;

		return $this;
	}
		
	/**
	 * @return boolean|NULL
	 */
	public function getSpam() {
		return $this->spam;
	}
}