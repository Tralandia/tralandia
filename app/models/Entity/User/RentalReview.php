<?php

namespace Entity\User;

use Entity\Dictionary;
use Entity\Rental;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="user_rentalreview", indexes={@ORM\index(name="senderEmail", columns={"senderEmail"})})
 */
class RentalReview extends \Entity\BaseEntity {

	// static const STATUS_PENDING = 0;
	// static const STATUS_APROVED = 1;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entity\Dictionary\Language")
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
	 * @var string
	 * @ORM\Column(type="string")
	 */
	protected $senderName;

	/**
	 * @var phone
	 * @ORM\Column(type="phone")
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


//@entity-generator-code <--- NEMAZAT !!!

	/* ----------------------------- Methods ----------------------------- */		
	public function __construct() {
		parent::__construct();
	}
		
	/**
	 * @param \Entity\Dictionary\Language
	 * @return \Entity\User\RentalReview
	 */
	public function setLanguage(\Entity\Dictionary\Language $language) {
		$this->language = $language;

		return $this;
	}
		
	/**
	 * @return \Entity\User\RentalReview
	 */
	public function unsetLanguage() {
		$this->language = NULL;

		return $this;
	}
		
	/**
	 * @return \Entity\Dictionary\Language|NULL
	 */
	public function getLanguage() {
		return $this->language;
	}
		
	/**
	 * @param \Entity\Rental\Rental
	 * @return \Entity\User\RentalReview
	 */
	public function setRental(\Entity\Rental\Rental $rental) {
		$this->rental = $rental;

		return $this;
	}
		
	/**
	 * @return \Entity\User\RentalReview
	 */
	public function unsetRental() {
		$this->rental = NULL;

		return $this;
	}
		
	/**
	 * @return \Entity\Rental\Rental|NULL
	 */
	public function getRental() {
		return $this->rental;
	}
		
	/**
	 * @param \Extras\Types\Email
	 * @return \Entity\User\RentalReview
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
	 * @param string
	 * @return \Entity\User\RentalReview
	 */
	public function setSenderName($senderName) {
		$this->senderName = $senderName;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getSenderName() {
		return $this->senderName;
	}
		
	/**
	 * @param \Extras\Types\Phone
	 * @return \Entity\User\RentalReview
	 */
	public function setSenderPhone(\Extras\Types\Phone $senderPhone) {
		$this->senderPhone = $senderPhone;

		return $this;
	}
		
	/**
	 * @return \Extras\Types\Phone|NULL
	 */
	public function getSenderPhone() {
		return $this->senderPhone;
	}
		
	/**
	 * @param \DateTime
	 * @return \Entity\User\RentalReview
	 */
	public function setArrivalDate(\DateTime $arrivalDate) {
		$this->arrivalDate = $arrivalDate;

		return $this;
	}
		
	/**
	 * @return \Entity\User\RentalReview
	 */
	public function unsetArrivalDate() {
		$this->arrivalDate = NULL;

		return $this;
	}
		
	/**
	 * @return \DateTime|NULL
	 */
	public function getArrivalDate() {
		return $this->arrivalDate;
	}
		
	/**
	 * @param \DateTime
	 * @return \Entity\User\RentalReview
	 */
	public function setDepartureDate(\DateTime $departureDate) {
		$this->departureDate = $departureDate;

		return $this;
	}
		
	/**
	 * @return \Entity\User\RentalReview
	 */
	public function unsetDepartureDate() {
		$this->departureDate = NULL;

		return $this;
	}
		
	/**
	 * @return \DateTime|NULL
	 */
	public function getDepartureDate() {
		return $this->departureDate;
	}
		
	/**
	 * @param json
	 * @return \Entity\User\RentalReview
	 */
	public function setCapacity($capacity) {
		$this->capacity = $capacity;

		return $this;
	}
		
	/**
	 * @return json|NULL
	 */
	public function getCapacity() {
		return $this->capacity;
	}
		
	/**
	 * @param string
	 * @return \Entity\User\RentalReview
	 */
	public function setMessage($message) {
		$this->message = $message;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getMessage() {
		return $this->message;
	}
}