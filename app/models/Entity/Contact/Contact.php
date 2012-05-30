<?php

namespace Entity\Contact;

use Entity\Attraction;
use Doctrine\ORM\Mapping as ORM;
use	Extras\Annotation as EA;

/**
 * @ORM\Entity()
 * @ORM\Table(name="contact_contact", indexes={@ORM\index(name="value", columns={"value"}), @ORM\index(name="subscribed", columns={"subscribed"}), @ORM\index(name="banned", columns={"banned"}), @ORM\index(name="full", columns={"full"}), @ORM\index(name="spam", columns={"spam"})})
 * @EA\Service(name="\Service\Contact\Contact")
 * @EA\ServiceList(name="\Service\Contact\ContactList")
 * @EA\Primary(key="id", value="value")
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
	 * @ORM\ManyToOne(targetEntity="Entity\Attraction\Attraction", inversedBy="contacts", cascade={"persist"})
	 */
	protected $attraction;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entity\Rental\Rental", inversedBy="contacts", cascade={"persist"})
	 */
	protected $rental;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entity\User\User", inversedBy="contacts", cascade={"persist"})
	 */
	protected $user;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entity\Location\Location", inversedBy="contacts", cascade={"persist"})
	 */
	protected $location;

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
	public function setAttraction(\Entity\Attraction\Attraction $attraction) {
		$this->attraction = $attraction;

		return $this;
	}
		
	/**
	 * @return \Entity\Contact\Contact
	 */
	public function unsetAttraction() {
		$this->attraction = NULL;

		return $this;
	}
		
	/**
	 * @return \Entity\Attraction\Attraction|NULL
	 */
	public function getAttraction() {
		return $this->attraction;
	}
		
	/**
	 * @param \Entity\Rental\Rental
	 * @return \Entity\Contact\Contact
	 */
	public function setRental(\Entity\Rental\Rental $rental) {
		$this->rental = $rental;

		return $this;
	}
		
	/**
	 * @return \Entity\Contact\Contact
	 */
	public function unsetRental() {
		$this->rental = NULL;

		return $this;
	}
		
	/**
	 * @return \Entity\Rental\Rental|NULL
	 */
	public function getRental() {
		return $this->rental;
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
	public function setLocation(\Entity\Location\Location $location) {
		$this->location = $location;

		return $this;
	}
		
	/**
	 * @return \Entity\Contact\Contact
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