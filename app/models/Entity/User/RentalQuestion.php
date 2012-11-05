<?php

namespace Entity\User;

use Entity\Phrase;
use Entity\Rental;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="user_rentalquestion", indexes={@ORM\index(name="senderEmail", columns={"senderEmail"})})
 */
class RentalQuestion extends \Entity\BaseEntity {

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
	 * @var phone
	 * @ORM\Column(type="phone")
	 */
	protected $senderPhone;

	/**
	 * @var text
	 * @ORM\Column(type="text")
	 */
	protected $question;


			//@entity-generator-code --- NEMAZAT !!!

	/* ----------------------------- Methods ----------------------------- */		
	public function __construct()
	{
		parent::__construct();
	}
		
	/**
	 * @param \Entity\Language
	 * @return \Entity\User\RentalQuestion
	 */
	public function setLanguage(\Entity\Language $language)
	{
		$this->language = $language;

		return $this;
	}
		
	/**
	 * @return \Entity\User\RentalQuestion
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
	 * @return \Entity\User\RentalQuestion
	 */
	public function setRental(\Entity\Rental\Rental $rental)
	{
		$this->rental = $rental;

		return $this;
	}
		
	/**
	 * @return \Entity\User\RentalQuestion
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
	 * @return \Entity\User\RentalQuestion
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
	 * @param \Extras\Types\Phone
	 * @return \Entity\User\RentalQuestion
	 */
	public function setSenderPhone(\Extras\Types\Phone $senderPhone)
	{
		$this->senderPhone = $senderPhone;

		return $this;
	}
		
	/**
	 * @return \Extras\Types\Phone|NULL
	 */
	public function getSenderPhone()
	{
		return $this->senderPhone;
	}
		
	/**
	 * @param string
	 * @return \Entity\User\RentalQuestion
	 */
	public function setQuestion($question)
	{
		$this->question = $question;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getQuestion()
	{
		return $this->question;
	}
}