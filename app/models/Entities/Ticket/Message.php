<?php

namespace Entities\Ticket;

use Entities\Dictionary;
use Entities\Medium;
use Entities\Ticket;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="ticket_message")
 */
class Message extends \BaseEntity {

	/**
	 * @var Collection
	 * @ORM\ManyToMany(type="Ticket")
	 */
	protected $ticket;

	/**
	 * @var email
	 * @ORM\ManyToMany(type="email")
	 */
	protected $senderEmail;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Dictionary\Phrase")
	 */
	protected $message;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Medium\Medium")
	 */
	protected $attachments;


	public function __construct() {
		parent::__construct();

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
