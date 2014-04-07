<?php

namespace Entity\User;

use Entity\Phrase;
use Entity\Rental;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="user_rentalreservation", indexes={@ORM\Index(name="senderEmail", columns={"senderEmail"})})
 *
 * @method \Entity\Rental\Unit[] getUnits()
 * @method setStatus($status)
 * @method string getStatus()
 * @method setChildrenAge($age)
 * @method int|null getChildrenAge()
 * @method setOwnersNote($note)
 * @method string getOwnersNote()
 * @method setReferrer($referrer)
 * @method string getReferrer()
 * @method setTotalPrice($price)
 * @method int|null getTotalPrice()
 * @method setPaidPrice($price)
 * @method int|null getPaidPrice()
 * @method setCurrency(\Entity\Currency $currency)
 * @method \Entity\Currency getCurrency()
 */
class RentalReservation extends \Entity\BaseEntity implements \Security\IOwnerable {

	const STATUS_CONFIRMED = 'confirmed';
	const STATUS_OPENED = 'opened';
	const STATUS_CANCELED = 'canceled';

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
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Entity\Rental\Unit")
	 * @ORM\JoinTable(name="_reservation_unit",
	 *      joinColumns={@ORM\JoinColumn(name="reservation_id", referencedColumnName="id", onDelete="CASCADE")},
	 *      inverseJoinColumns={@ORM\JoinColumn(name="unit_id", referencedColumnName="id", onDelete="CASCADE")}
	 *      )
	 */
	protected $units;

	/**
	 * @var string
	 * @ORM\Column(type="string")
	 */
	protected $status = self::STATUS_OPENED;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
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
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $senderRemoteAddress;

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
	 * @ORM\Column(type="integer", nullable=true)
	 */
	protected $adultsCount;

	/**
	 * @var integer
	 * @ORM\Column(type="integer", nullable=true)
	 */
	protected $childrenCount;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $childrenAge;

	/**
	 * @var string
	 * @ORM\Column(type="text", nullable=true)
	 */
	protected $message;

	/**
	 * @var string
	 * @ORM\Column(type="text", nullable=true)
	 */
	protected $ownersNote;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $referrer;

	/**
	 * @var float
	 * @ORM\Column(type="float", nullable=true)
	 */
	protected $totalPrice;

	/**
	 * @var float
	 * @ORM\Column(type="float", nullable=true)
	 */
	protected $paidPrice = 0;


	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entity\Currency")
	 */
	protected $currency;


	public function getOwnerId()
	{
		$rental = $this->getRental();
		if(!$rental) {
			$unit = $this->units->first();
			if($unit) $rental = $unit->getRental();
		}
		if($rental) return $rental->getOwnerId();

		return NULL;
	}


	public function addUnit(\Entity\Rental\Unit $unit)
	{
		if(!$this->units->contains($unit)) {
			$this->units->add($unit);
		}

		return $this;
	}


	public function removeUnit(\Entity\Rental\Unit $unit)
	{
		$this->units->removeElement($unit);

		return $this;
	}



	//@entity-generator-code --- NEMAZAT !!!

	/* ----------------------------- Methods ----------------------------- */
	public function __construct()
	{
		$this->units = new \Doctrine\Common\Collections\ArrayCollection;
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
	 * @return \Entity\Rental\Rental|null
	 */
	public function getRental()
	{
		return $this->rental;
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
	 * @param string
	 * @return \Entity\User\RentalReservation
	 */
	public function setSenderRemoteAddress($remoteAddress)
	{
		$this->senderRemoteAddress = $remoteAddress;

		return $this;
	}

	/**
	 * @return string|NULL
	 */
	public function getSenderRemoteAddress()
	{
		return $this->senderRemoteAddress;
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
