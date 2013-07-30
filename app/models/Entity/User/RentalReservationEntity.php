<?php

namespace Entity\User;

use Entity\Phrase;
use Entity\Rental;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Repository\User\RentalReservationRepository")
 * @ORM\Table(name="user_rentalreservation", indexes={@ORM\index(name="senderEmail", columns={"senderEmail"})})
 */
class RentalReservation extends \Entity\BaseEntity {

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entity\Language")
	 */
	protected $language;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entity\Rental\Rental")
	 * @ORM\JoinColumn(onDelete="CASCADE")
	 */
	protected $rental;

	/**
	 * @var string
	 * @ORM\Column(type="string")
	 */
	protected $senderEmail;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
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
	 * @var integer
	 * @ORM\Column(type="integer")
	 */
	protected $adultsCount;

	/**
	 * @var integer
	 * @ORM\Column(type="integer", nullable=true)
	 */
	protected $childrenCount;

	/**
	 * @var string
	 * @ORM\Column(type="text", nullable=true)
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
	 * @return \Entity\User\RentalReservation
	 */
	public function unsetSenderPhone()
	{
		$this->senderPhone = NULL;

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
	 * @param integer
	 * @return \Entity\User\RentalReservation
	 */
	public function setAdultsCount($adultsCount)
	{
		$this->adultsCount = $adultsCount;

		return $this;
	}

	/**
	 * @return integer|NULL
	 */
	public function getAdultsCount()
	{
		return $this->adultsCount;
	}

	/**
	 * @param integer
	 * @return \Entity\User\RentalReservation
	 */
	public function setChildrenCount($childrenCount)
	{
		$this->childrenCount = $childrenCount;

		return $this;
	}

	/**
	 * @return \Entity\User\RentalReservation
	 */
	public function unsetChildrenCount()
	{
		$this->childrenCount = NULL;

		return $this;
	}

	/**
	 * @return integer|NULL
	 */
	public function getChildrenCount()
	{
		return $this->childrenCount;
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
