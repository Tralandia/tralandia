<?php

namespace Entity\User;

use Entity\Phrase;
use Entity\Rental;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="user_rentaltofriend", indexes={@ORM\index(name="senderEmail", columns={"senderEmail"})})
 */
class RentalToFriend extends \Entity\BaseEntity {

	// static const STATUS_PENDING = 0;
	// static const STATUS_APROVED = 1;

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
	 * @var email
	 * @ORM\Column(type="email")
	 */
	protected $senderEmail;

	/**
	 * @var email
	 * @ORM\Column(type="email")
	 */
	protected $receiverEmail;

	/**
	 * @var text
	 * @ORM\Column(type="text")
	 */
	protected $message;


				//@entity-generator-code --- NEMAZAT !!!

	/* ----------------------------- Methods ----------------------------- */		
	public function __construct()
	{
		parent::__construct();
	}
		
	/**
	 * @param \Entity\Language
	 * @return \Entity\User\RentalToFriend
	 */
	public function setLanguage(\Entity\Language $language)
	{
		$this->language = $language;

		return $this;
	}
		
	/**
	 * @return \Entity\User\RentalToFriend
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
	 * @return \Entity\User\RentalToFriend
	 */
	public function setRental(\Entity\Rental\Rental $rental)
	{
		$this->rental = $rental;

		return $this;
	}
		
	/**
	 * @return \Entity\User\RentalToFriend
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
	 * @param \Extras\Types\Email
	 * @return \Entity\User\RentalToFriend
	 */
	public function setSenderEmail(\Extras\Types\Email $senderEmail)
	{
		$this->senderEmail = $senderEmail;

		return $this;
	}
		
	/**
	 * @return \Extras\Types\Email|NULL
	 */
	public function getSenderEmail()
	{
		return $this->senderEmail;
	}
		
	/**
	 * @param \Extras\Types\Email
	 * @return \Entity\User\RentalToFriend
	 */
	public function setReceiverEmail(\Extras\Types\Email $receiverEmail)
	{
		$this->receiverEmail = $receiverEmail;

		return $this;
	}
		
	/**
	 * @return \Extras\Types\Email|NULL
	 */
	public function getReceiverEmail()
	{
		return $this->receiverEmail;
	}
		
	/**
	 * @param string
	 * @return \Entity\User\RentalToFriend
	 */
	public function setMessage($message)
	{
		$this->message = $message;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getMessage()
	{
		return $this->message;
	}
}