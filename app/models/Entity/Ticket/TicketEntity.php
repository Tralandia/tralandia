<?php

namespace Entity\Ticket;

use Entity\Phrase;
use Entity\Location;
use Entity\User;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="ticket")
 */
class Ticket extends \Entity\BaseEntity {

	const STATUS_PENDING = TRUE;
	const STATUS_REPLIED = FALSE;

	/**
	 * @var boolean
	 * @ORM\Column(type="boolean")
	 */
	protected $status = self::STATUS_PENDING;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entity\Language")
	 */
	protected $language;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entity\Rental\Rental")
	 */
	protected $rental;

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
	 * @param boolean
	 * @return \Entity\Ticket\Ticket
	 */
	public function setStatus($status)
	{
		$this->status = $status;

		return $this;
	}
		
	/**
	 * @return boolean|NULL
	 */
	public function getStatus()
	{
		return $this->status;
	}
		
	/**
	 * @param \Entity\Language
	 * @return \Entity\Ticket\Ticket
	 */
	public function setLanguage(\Entity\Language $language)
	{
		$this->language = $language;

		return $this;
	}
		
	/**
	 * @return \Entity\Ticket\Ticket
	 */
	public function unsetLanguage()
	{
		$this->language = NULL;

		return $this;
	}
		
	/**
	 * @return \Entity\Language|NULL
	 */
	public function getLanguage()
	{
		return $this->language;
	}
		
	/**
	 * @param \Entity\Rental\Rental
	 * @return \Entity\Ticket\Ticket
	 */
	public function setRental(\Entity\Rental\Rental $rental)
	{
		$this->rental = $rental;

		return $this;
	}
		
	/**
	 * @return \Entity\Ticket\Ticket
	 */
	public function unsetRental()
	{
		$this->rental = NULL;

		return $this;
	}
		
	/**
	 * @return \Entity\Rental\Rental|NULL
	 */
	public function getRental()
	{
		return $this->rental;
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