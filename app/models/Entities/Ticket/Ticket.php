<?php

namespace Ticket;

use Dictionary;
use Location;
use Ticket;
use User;
use Doctrine\Common\Collections\Collection
use Doctrine\Common\Collections\ArrayCollection


/**
 * @Entity()
 * @Table(name="ticket_ticket")
 */
class Ticket extends \BaseEntity {

	/**
	 * @var email
	 * @Column(type="email")
	 */
	protected $client;

	/**
	 * @var Collection
	 * @OneToOne(targetEntity="User\User")
	 */
	protected $staff;

	/**
	 * @var Collection
	 * @ManyToMany(targetEntity="Location\Location")
	 */
	protected $country;

	/**
	 * @var Collection
	 * @ManyToMany(targetEntity="Dictionary\Language")
	 */
	protected $language;

	/**
	 * @var Collection
	 * @Column(type="Status")
	 */
	protected $status;

	/**
	 * @var Collection
	 * @Column(type="Message")
	 */
	protected $messages;


	public function __construct() {

	}


	/**
	 * @param email $client
	 * @return Ticket
	 */
	public function setClient($client) {
		$this->client = $client;
		return $this;
	}


	/**
	 * @return email
	 */
	public function getClient() {
		return $this->client;
	}


	/**
	 * @param User\User $staff
	 * @return Ticket
	 */
	public function setStaff(User\User  $staff) {
		$this->staff = $staff;
		return $this;
	}


	/**
	 * @return User\User
	 */
	public function getStaff() {
		return $this->staff;
	}


	/**
	 * @param Location\Location $country
	 * @return Ticket
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
	 * @param Dictionary\Language $language
	 * @return Ticket
	 */
	public function setLanguage(Dictionary\Language  $language) {
		$this->language = $language;
		return $this;
	}


	/**
	 * @return Dictionary\Language
	 */
	public function getLanguage() {
		return $this->language;
	}


	/**
	 * @param Status $status
	 * @return Ticket
	 */
	public function setStatus(Status  $status) {
		$this->status = $status;
		return $this;
	}


	/**
	 * @return Status
	 */
	public function getStatus() {
		return $this->status;
	}


	/**
	 * @param Message $messages
	 * @return Ticket
	 */
	public function setMessages(Message  $messages) {
		$this->messages = $messages;
		return $this;
	}


	/**
	 * @return Message
	 */
	public function getMessages() {
		return $this->messages;
	}

}
