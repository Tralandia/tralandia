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
	 * @var Collection
	 * @ORM\OneToMany(targetEntity="Message", mappedBy="ticket", cascade={"persist", "remove"})
	 */
	protected $messages;


	//@entity-generator-code --- NEMAZAT !!!

	/* ----------------------------- Methods ----------------------------- */		
	public function __construct()
	{
		parent::__construct();

		$this->messages = new \Doctrine\Common\Collections\ArrayCollection;
	}
		
	/**
	 * @param \Entity\Ticket\Message
	 * @return \Entity\Ticket\Ticket
	 */
	public function addMessage(\Entity\Ticket\Message $message)
	{
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
	public function removeMessage(\Entity\Ticket\Message $message)
	{
		if($this->messages->contains($message)) {
			$this->messages->removeElement($message);
		}
		$message->unsetTicket();

		return $this;
	}
		
	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection of \Entity\Ticket\Message
	 */
	public function getMessages()
	{
		return $this->messages;
	}
}