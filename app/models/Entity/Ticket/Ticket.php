<?php

namespace Entity\Ticket;

use Entity\Dictionary;
use Entity\Location;
use Entity\User;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="ticket_ticket")
 */
class Ticket extends \Entity\BaseEntity {

	/**
	 * @var email
	 * @ORM\Column(type="email")
	 */
	protected $client;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entity\User\User")
	 */
	protected $staff;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entity\Location\Location")
	 */
	protected $country;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entity\Dictionary\Language")
	 */
	protected $language;

	/**
	 * @var integer
	 * @ORM\Column(type="integer")
	 */
	protected $status;

	/**
	 * @var Collection
	 * @ORM\OneToMany(targetEntity="Message", mappedBy="ticket")
	 */
	protected $messages;

	




//@entity-generator-code <--- NEMAZAT !!!

	/* ----------------------------- Methods ----------------------------- */		
	public function __construct() {
		parent::__construct();

		$this->messages = new \Doctrine\Common\Collections\ArrayCollection;
	}
		
	/**
	 * @param \Extras\Types\Email
	 * @return \Entity\Ticket\Ticket
	 */
	public function setClient(\Extras\Types\Email $client) {
		$this->client = $client;

		return $this;
	}
		
	/**
	 * @return \Extras\Types\Email|NULL
	 */
	public function getClient() {
		return $this->client;
	}
		
	/**
	 * @param \Entity\User\User
	 * @return \Entity\Ticket\Ticket
	 */
	public function setStaff(\Entity\User\User $staff) {
		$this->staff = $staff;

		return $this;
	}
		
	/**
	 * @return \Entity\Ticket\Ticket
	 */
	public function unsetStaff() {
		$this->staff = NULL;

		return $this;
	}
		
	/**
	 * @return \Entity\User\User|NULL
	 */
	public function getStaff() {
		return $this->staff;
	}
		
	/**
	 * @param \Entity\Location\Location
	 * @return \Entity\Ticket\Ticket
	 */
	public function setCountry(\Entity\Location\Location $country) {
		$this->country = $country;

		return $this;
	}
		
	/**
	 * @return \Entity\Ticket\Ticket
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
	 * @param \Entity\Dictionary\Language
	 * @return \Entity\Ticket\Ticket
	 */
	public function setLanguage(\Entity\Dictionary\Language $language) {
		$this->language = $language;

		return $this;
	}
		
	/**
	 * @return \Entity\Ticket\Ticket
	 */
	public function unsetLanguage() {
		$this->language = NULL;

		return $this;
	}
		
	/**
	 * @return \Entity\Dictionary\Language|NULL
	 */
	public function getLanguage() {
		return $this->language;
	}
		
	/**
	 * @param integer
	 * @return \Entity\Ticket\Ticket
	 */
	public function setStatus($status) {
		$this->status = $status;

		return $this;
	}
		
	/**
	 * @return integer|NULL
	 */
	public function getStatus() {
		return $this->status;
	}
		
	/**
	 * @param \Entity\Ticket\Message
	 * @return \Entity\Ticket\Ticket
	 */
	public function addMessage(\Entity\Ticket\Message $message) {
		if(!$this->messages->contains($message)) {
			$this->messages->add($message);
		}
		$message->setTicket($this);

		return $this;
	}
		
	/**
	 * @param \Entity\Ticket\Message
	 * @return \Entity\Ticket\Ticket
	 */
	public function removeMessage(\Entity\Ticket\Message $message) {
		if($this->messages->contains($message)) {
			$this->messages->removeElement($message);
		}
		$message->unsetTicket();

		return $this;
	}
		
	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection of \Entity\Ticket\Message
	 */
	public function getMessages() {
		return $this->messages;
	}
}