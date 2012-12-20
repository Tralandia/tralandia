<?php

namespace Entity\Ticket;

use Entity\Phrase;
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
	 * @var boolean
	 * @ORM\Column(type="boolean")
	 */
	protected $translated = 0;

	/**
	 * @var Collection
	 * @ORM\OneToMany(targetEntity="Entity\Medium\Medium", mappedBy="message", cascade={"persist", "remove"})
	 */
	protected $attachments;


			//@entity-generator-code --- NEMAZAT !!!

	/* ----------------------------- Methods ----------------------------- */		
	public function __construct()
	{
		parent::__construct();

		$this->attachments = new \Doctrine\Common\Collections\ArrayCollection;
	}
		
	/**
	 * @param \Entity\Ticket\Ticket
	 * @return \Entity\Ticket\Message
	 */
	public function setTicket(\Entity\Ticket\Ticket $ticket)
	{
		$this->ticket = $ticket;

		return $this;
	}
		
	/**
	 * @return \Entity\Ticket\Message
	 */
	public function unsetTicket()
	{
		$this->ticket = NULL;

		return $this;
	}
		
	/**
	 * @return \Entity\Ticket\Ticket|NULL
	 */
	public function getTicket()
	{
		return $this->ticket;
	}
		
	/**
	 * @param \Entity\User\User
	 * @return \Entity\Ticket\Message
	 */
	public function setFrom(\Entity\User\User $from)
	{
		$this->from = $from;

		return $this;
	}
		
	/**
	 * @return \Entity\Ticket\Message
	 */
	public function unsetFrom()
	{
		$this->from = NULL;

		return $this;
	}
		
	/**
	 * @return \Entity\User\User|NULL
	 */
	public function getFrom()
	{
		return $this->from;
	}
		
	/**
	 * @param string
	 * @return \Entity\Ticket\Message
	 */
	public function setMessage($message)
	{
		$this->message = $message;

		return $this;
	}
		
	/**
	 * @return \Entity\Ticket\Message
	 */
	public function unsetMessage()
	{
		$this->message = NULL;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getMessage()
	{
		return $this->message;
	}
		
	/**
	 * @param string
	 * @return \Entity\Ticket\Message
	 */
	public function setMessageEn($messageEn)
	{
		$this->messageEn = $messageEn;

		return $this;
	}
		
	/**
	 * @return \Entity\Ticket\Message
	 */
	public function unsetMessageEn()
	{
		$this->messageEn = NULL;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getMessageEn()
	{
		return $this->messageEn;
	}
		
	/**
	 * @param boolean
	 * @return \Entity\Ticket\Message
	 */
	public function setTranslated($translated)
	{
		$this->translated = $translated;

		return $this;
	}
		
	/**
	 * @return boolean|NULL
	 */
	public function getTranslated()
	{
		return $this->translated;
	}
		
	/**
	 * @param \Entity\Medium\Medium
	 * @return \Entity\Ticket\Message
	 */
	public function addAttachment(\Entity\Medium\Medium $attachment)
	{
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
	public function removeAttachment(\Entity\Medium\Medium $attachment)
	{
		$this->attachments->removeElement($attachment);
		$attachment->unsetMessage();

		return $this;
	}
		
	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection of \Entity\Medium\Medium
	 */
	public function getAttachments()
	{
		return $this->attachments;
	}
}