<?php

namespace Entity\Ticket;

use Entity\Dictionary;
use Entity\Medium;
use Entity\Ticket;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="ticket_message")
 */
class Message extends \Entity\BaseEntity {

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Ticket", inversedBy="messages")
	 */
	protected $ticket;

	/**
	 * @var email
	 * @ORM\Column(type="email")
	 */
	protected $senderEmail;

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Entity\Dictionary\Phrase", cascade={"persist", "remove"})
	 */
	protected $message;

	/**
	 * @var Collection
	 * @ORM\OneToMany(targetEntity="Entity\Medium\Medium", mappedBy="message")
	 */
	protected $attachments;

	




//@entity-generator-code <--- NEMAZAT !!!

	/* ----------------------------- Methods ----------------------------- */		
	public function __construct() {
		parent::__construct();

		$this->attachments = new \Doctrine\Common\Collections\ArrayCollection;
	}
		
	/**
	 * @param \Entity\Ticket\Ticket
	 * @return \Entity\Ticket\Message
	 */
	public function setTicket(\Entity\Ticket\Ticket $ticket) {
		$this->ticket = $ticket;

		return $this;
	}
		
	/**
	 * @return \Entity\Ticket\Message
	 */
	public function unsetTicket() {
		$this->ticket = NULL;

		return $this;
	}
		
	/**
	 * @return \Entity\Ticket\Ticket|NULL
	 */
	public function getTicket() {
		return $this->ticket;
	}
		
	/**
	 * @param \Extras\Types\Email
	 * @return \Entity\Ticket\Message
	 */
	public function setSenderEmail(\Extras\Types\Email $senderEmail) {
		$this->senderEmail = $senderEmail;

		return $this;
	}
		
	/**
	 * @return \Extras\Types\Email|NULL
	 */
	public function getSenderEmail() {
		return $this->senderEmail;
	}
		
	/**
	 * @param \Entity\Dictionary\Phrase
	 * @return \Entity\Ticket\Message
	 */
	public function setMessage(\Entity\Dictionary\Phrase $message) {
		$this->message = $message;

		return $this;
	}
		
	/**
	 * @return \Entity\Dictionary\Phrase|NULL
	 */
	public function getMessage() {
		return $this->message;
	}
		
	/**
	 * @param \Entity\Medium\Medium
	 * @return \Entity\Ticket\Message
	 */
	public function addAttachment(\Entity\Medium\Medium $attachment) {
		if(!$this->attachments->contains($attachment)) {
			$this->attachments->add($attachment);
		}
		$attachment->setMessage($this);

		return $this;
	}
		
	/**
	 * @param \Entity\Medium\Medium
	 * @return \Entity\Ticket\Message
	 */
	public function removeAttachment(\Entity\Medium\Medium $attachment) {
		if($this->attachments->contains($attachment)) {
			$this->attachments->removeElement($attachment);
		}
		$attachment->unsetMessage();

		return $this;
	}
		
	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection of \Entity\Medium\Medium
	 */
	public function getAttachments() {
		return $this->attachments;
	}
}