<?php

namespace Entity\User;

use Entity\Phrase;
use Entity\Rental;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="user_rentalreview")
 */
class RentalReview extends \Entity\BaseEntity {

	 const STATUS_BANNED = 0;
	 const STATUS_LIVE = 1;

	/**
	 * @var int
	 * @ORM\Column(type="integer")
	 */
	protected $status = self::STATUS_LIVE;

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
	protected $senderFirstName;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $senderLastName;

	/**
	 * @var string
	 * @ORM\Column(type="string")
	 */
	protected $group;

	/**
	 * @var \Datetime
	 * @ORM\Column(type="datetime", nullable=true)
	 */
	protected $arrivalDate;

	/**
	 * @var \Datetime
	 * @ORM\Column(type="datetime", nullable=true)
	 */
	protected $departureDate;

	/**
	 * @var string
	 * @ORM\Column(type="text")
	 */
	protected $messagePros;

	/**
	 * @var string
	 * @ORM\Column(type="text")
	 */
	protected $messageNegative;

	/**
	 * @var string
	 * @ORM\Column(type="text")
	 */
	protected $messageLocality;

	/**
	 * @var string
	 * @ORM\Column(type="text")
	 */
	protected $messageRegion;


	/**
	 * @var int
	 * @ORM\Column(type="integer")
	 */
	protected $pointsLocation;

	/**
	 * @var int
	 * @ORM\Column(type="integer")
	 */
	protected $pointsCleanness;

	/**
	 * @var int
	 * @ORM\Column(type="integer")
	 */
	protected $pointsAmenities;

	/**
	 * @var int
	 * @ORM\Column(type="integer")
	 */
	protected $pointsPersonal;

	/**
	 * @var int
	 * @ORM\Column(type="integer")
	 */
	protected $pointsServices;

	/**
	 * @var int
	 * @ORM\Column(type="integer")
	 */
	protected $pointsAttractions;

	/**
	 * @var int
	 * @ORM\Column(type="integer")
	 */
	protected $pointsPrice;


}
