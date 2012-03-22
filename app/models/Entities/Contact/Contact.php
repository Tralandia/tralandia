<?php

namespace Entities\Contact;

use Entities\Attraction;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="contact_contact")
 */
class Contact extends \Entities\BaseEntity {

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Type")
	 */
	protected $type;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $value;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Entities\Attraction\Attraction", inversedBy="contacts")
	 */
	protected $attractions;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Entities\Rental\Rental", inversedBy="contacts")
	 */
	protected $rentals;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entities\User\User", inversedBy="contact")
	 */
	protected $user;

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



	public function __construct() {
		parent::__construct();

		$this->attractions = new \Doctrine\Common\Collections\ArrayCollection;
		$this->rentals = new \Doctrine\Common\Collections\ArrayCollection;
	}

	/**
	 * @param \Entities\Contact\Type
	 * @return \Entities\Contact\Contact
	 */
	public function setType(\Entities\Contact\Type $type) {
		$this->type = $type;

		return $this;
	}

	/**
	 * @return \Entities\Contact\Contact
	 */
	public function unsetType() {
		$this->type = NULL;

		return $this;
	}

	/**
	 * @return \Entities\Contact\Type|NULL
	 */
	public function getType() {
		return $this->type;
	}

	/**
	 * @param string
	 * @return \Entities\Contact\Contact
	 */
	public function setValue($value) {
		$this->value = $value;

		return $this;
	}

	/**
	 * @return \Entities\Contact\Contact
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
	 * @param \Entities\Attraction\Attraction
	 * @return \Entities\Contact\Contact
	 */
	public function addAttraction(\Entities\Attraction\Attraction $attraction) {
		if(!$this->attractions->contains($attraction)) {
			$this->attractions->add($attraction);
		}
		$attraction->addContact($this);

		return $this;
	}

	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection of \Entities\Attraction\Attraction
	 */
	public function getAttractions() {
		return $this->attractions;
	}

	/**
	 * @param \Entities\Rental\Rental
	 * @return \Entities\Contact\Contact
	 */
	public function addRental(\Entities\Rental\Rental $rental) {
		if(!$this->rentals->contains($rental)) {
			$this->rentals->add($rental);
		}
		$rental->addContact($this);

		return $this;
	}

	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection of \Entities\Rental\Rental
	 */
	public function getRentals() {
		return $this->rentals;
	}

	/**
	 * @param \Entities\User\User
	 * @return \Entities\Contact\Contact
	 */
	public function setUser(\Entities\User\User $user) {
		$this->user = $user;

		return $this;
	}

	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection of \Entities\User\User
	 */
	public function getUser() {
		return $this->user;
	}

	/**
	 * @param json
	 * @return \Entities\Contact\Contact
	 */
	public function setInfo($info) {
		$this->info = $info;

		return $this;
	}

	/**
	 * @return \Entities\Contact\Contact
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
	 * @return \Entities\Contact\Contact
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
	 * @return \Entities\Contact\Contact
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
	 * @return \Entities\Contact\Contact
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
	 * @return \Entities\Contact\Contact
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

	/**
	 * @param integer
	 * @return \Entities\BaseEntity
	 */
	public function setOldId($oldId) {
		$this->oldId = $oldId;

		return $this;
	}

	/**
	 * @return \Entities\BaseEntity
	 */
	public function unsetOldId() {
		$this->oldId = NULL;

		return $this;
	}

	/**
	 * @return integer|NULL
	 */
	public function getOldId() {
		return $this->oldId;
	}

}