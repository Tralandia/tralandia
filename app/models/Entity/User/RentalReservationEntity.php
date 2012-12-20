<?php

namespace Entity\User;

use Entity\Phrase;
use Entity\Rental;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="user_rentalreservation", indexes={@ORM\index(name="senderEmail", columns={"senderEmail"})})
 */
class RentalReservation extends \Entity\BaseEntity {

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
	 * @var string
	 * @ORM\Column(type="string")
	 */
	protected $senderEmail;

	/**
	 * @var string
	 * @ORM\Column(type="string")
	 */
	protected $senderName;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entity\Contact\Phone", cascade={"persist"})
	 */
	protected $senderPhone;

	/**
	 * @var datetime
	 * @ORM\Column(type="datetime", nullable=true)
	 */
	protected $arrivalDate;

	/**
	 * @var datetime
	 * @ORM\Column(type="datetime", nullable=true)
	 */
	protected $departureDate;

	/**
	 * @var json
	 * @ORM\Column(type="json")
	 * all details about people / children / rooms will be here
	 */
	protected $capacity;

	/**
	 * @var string
	 * @ORM\Column(type="string")
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
	 * @return \Entity\User\RentalReservation
	 */
	public function setLanguage(\Entity\Language $language)
	{
		$this->language = $language;

		return $this;
	}
		
	/**
	 * @return \Entity\User\RentalReservation
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
	 * @return \Entity\User\RentalReservation
	 */
	public function setRental(\Entity\Rental\Rental $rental)
	{
		$this->rental = $rental;

		return $this;
	}
		
	/**
	 * @return \Entity\User\RentalReservation
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
	 * @param string
	 * @return \Entity\User\RentalReservation
	 */
	public function setSenderEmail($senderEmail)
	{
		$this->senderEmail = $senderEmail;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getSenderEmail()
	{
		return $this->senderEmail;
	}
		
	/**
	 * @param string
	 * @return \Entity\User\RentalReservation
	 */
	public function setSenderName($senderName)
	{
		$this->senderName = $senderName;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getSenderName()
	{
		return $this->senderName;
	}
		
	/**
	 * @param \Entity\Contact\Phone
	 * @return \Entity\User\RentalReservation
	 */
	public function setSenderPhone(\Entity\Contact\Phone $senderPhone)
	{
		$this->senderPhone = $senderPhone;

		return $this;
	}
		
	/**
	 * @return \Entity\Contact\Phone|NULL
	 */
	public function getSenderPhone()
	{
		return $this->senderPhone;
	}
		
	/**
	 * @param \DateTime
	 * @return \Entity\User\RentalReservation
	 */
	public function setArrivalDate(\DateTime $arrivalDate)
	{
		$this->arrivalDate = $arrivalDate;

		return $this;
	}
		
	/**
	 * @return \Entity\User\RentalReservation
	 */
	public function unsetArrivalDate()
	{
		$this->arrivalDate = NULL;

		return $this;
	}
		
	/**
	 * @return \DateTime|NULL
	 */
	public function getArrivalDate()
	{
		return $this->arrivalDate;
	}
		
	/**
	 * @param \DateTime
	 * @return \Entity\User\RentalReservation
	 */
	public function setDepartureDate(\DateTime $departureDate)
	{
		$this->departureDate = $departureDate;

		return $this;
	}
		
	/**
	 * @return \Entity\User\RentalReservation
	 */
	public function unsetDepartureDate()
	{
		$this->departureDate = NULL;

		return $this;
	}
		
	/**
	 * @return \DateTime|NULL
	 */
	public function getDepartureDate()
	{
		return $this->departureDate;
	}
		
	/**
	 * @param json
	 * @return \Entity\User\RentalReservation
	 */
	public function setCapacity($capacity)
	{
		$this->capacity = $capacity;

		return $this;
	}
		
	/**
	 * @return json|NULL
	 */
	public function getCapacity()
	{
		return $this->capacity;
	}
		
	/**
	 * @param string
	 * @return \Entity\User\RentalReservation
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