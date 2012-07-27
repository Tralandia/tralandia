<?php

namespace Entity\Ticket;

use Entity\Dictionary;
use Entity\Medium;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="ticket_message")
 */
class Message extends \Entity\BaseEntity {

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Ticket", inversedBy="messages", cascade={"persist", "remove"})
	 */
	protected $ticket;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entity\User\User")
	 */
	protected $from;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entity\User\User")
	 */
	protected $to;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Entity\User\User", inversedBy="ticketMessages")
	 */
	protected $toCC;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $subject;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $subjectEn;

	/**
	 * @var text
	 * @ORM\Column(type="text", nullable=true)
	 */
	protected $message;

	/**
	 * @var text
	 * @ORM\Column(type="text", nullable=true)
	 */
	protected $messageEn;

	/**
	 * @var Collection
	 * @ORM\OneToMany(targetEntity="Entity\Medium\Medium", mappedBy="message", cascade={"persist", "remove"})
	 */
	protected $attachments;


//@entity-generator-code <--- NEMAZAT !!!

	/* ----------------------------- Methods ----------------------------- */		
	public function __construct() {
		parent::__construct();

		$this->toCC = new \Doctrine\Common\Collections\ArrayCollection;
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
	 * @param \Entity\User\User
	 * @return \Entity\Ticket\Message
	 */
	public function setFrom(\Entity\User\User $from) {
		$this->from = $from;

		return $this;
	}
		
	/**
	 * @return \Entity\Ticket\Message
	 */
	public function unsetFrom() {
		$this->from = NULL;

		return $this;
	}
		
	/**
	 * @return \Entity\User\User|NULL
	 */
	public function getFrom() {
		return $this->from;
	}
		
	/**
	 * @param \Entity\User\User
	 * @return \Entity\Ticket\Message
	 */
	public function setTo(\Entity\User\User $to) {
		$this->to = $to;

		return $this;
	}
		
	/**
	 * @return \Entity\Ticket\Message
	 */
	public function unsetTo() {
		$this->to = NULL;

		return $this;
	}
		
	/**
	 * @return \Entity\User\User|NULL
	 */
	public function getTo() {
		return $this->to;
	}
		
	/**
	 * @param \Entity\User\User
	 * @return \Entity\Ticket\Message
	 */
	public function addToCC(\Entity\User\User $toCC) {
		if(!$this->toCC->contains($toCC)) {
			$this->toCC->add($toCC);
		}

		return $this;
	}
		
	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection of \Entity\User\User
	 */
	public function getToCC() {
		return $this->toCC;
	}
		
	/**
	 * @param string
	 * @return \Entity\Ticket\Message
	 */
	public function setSubject($subject) {
		$this->subject = $subject;

		return $this;
	}
		
	/**
	 * @return \Entity\Ticket\Message
	 */
	public function unsetSubject() {
		$this->subject = NULL;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getSubject() {
		return $this->subject;
	}
		
	/**
	 * @param string
	 * @return \Entity\Ticket\Message
	 */
	public function setSubjectEn($subjectEn) {
		$this->subjectEn = $subjectEn;

		return $this;
	}
		
	/**
	 * @return \Entity\Ticket\Message
	 */
	public function unsetSubjectEn() {
		$this->subjectEn = NULL;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getSubjectEn() {
		return $this->subjectEn;
	}
		
	/**
	 * @param string
	 * @return \Entity\Ticket\Message
	 */
	public function setMessage($message) {
		$this->message = $message;

		return $this;
	}
		
	/**
	 * @return \Entity\Ticket\Message
	 */
	public function unsetMessage() {
		$this->message = NULL;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getMessage() {
		return $this->message;
	}
		
	/**
	 * @param string
	 * @return \Entity\Ticket\Message
	 */
	public function setMessageEn($messageEn) {
		$this->messageEn = $messageEn;

		return $this;
	}
		
	/**
	 * @return \Entity\Ticket\Message
	 */
	public function unsetMessageEn() {
		$this->messageEn = NULL;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getMessageEn() {
		return $this->messageEn;
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