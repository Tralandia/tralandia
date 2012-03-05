<?php

namespace Ticket;

use Dictionary;
use Medium;
use Ticket;
use Doctrine\Common\Collections\Collection
use Doctrine\Common\Collections\ArrayCollection


/**
 * @Entity()
 * @Table(name="ticket_message")
 */
class Message extends \BaseEntity {

	/**
	 * @var Collection
	 * @Column(type="Ticket")
	 */
	protected $ticket;

	/**
	 * @var email
	 * @Column(type="email")
	 */
	protected $senderEmail;

	/**
	 * @var Collection
	 * @ManyToMany(targetEntity="Dictionary\Phrase")
	 */
	protected $message;

	/**
	 * @var Collection
	 * @ManyToMany(targetEntity="Medium\Medium")
	 */
	protected $attachments;


	public function __construct() {

	}


	/**
	 * @param Ticket $ticket
	 * @return Message
	 */
	public function setTicket(Ticket  $ticket) {
		$this->ticket = $ticket;
		return $this;
	}


	/**
	 * @return Ticket
	 */
	public function getTicket() {
		return $this->ticket;
	}


	/**
	 * @param email $senderEmail
	 * @return Message
	 */
	public function setSenderEmail($senderEmail) {
		$this->senderEmail = $senderEmail;
		return $this;
	}


	/**
	 * @return email
	 */
	public function getSenderEmail() {
		return $this->senderEmail;
	}


	/**
	 * @param Dictionary\Phrase $message
	 * @return Message
	 */
	public function setMessage(Dictionary\Phrase  $message) {
		$this->message = $message;
		return $this;
	}


	/**
	 * @return Dictionary\Phrase
	 */
	public function getMessage() {
		return $this->message;
	}


	/**
	 * @param Medium\Medium $attachments
	 * @return Message
	 */
	public function setAttachments(Medium\Medium  $attachments) {
		$this->attachments = $attachments;
		return $this;
	}


	/**
	 * @return Medium\Medium
	 */
	public function getAttachments() {
		return $this->attachments;
	}

}
