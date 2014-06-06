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
	protected $groupType;

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
	protected $messagePositives;

	/**
	 * @var string
	 * @ORM\Column(type="text")
	 */
	protected $messageNegatives;

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
	protected $ratingLocation;

	/**
	 * @var int
	 * @ORM\Column(type="integer")
	 */
	protected $ratingCleanness;

	/**
	 * @var int
	 * @ORM\Column(type="integer")
	 */
	protected $ratingAmenities;

	/**
	 * @var int
	 * @ORM\Column(type="integer")
	 */
	protected $ratingPersonal;

	/**
	 * @var int
	 * @ORM\Column(type="integer")
	 */
	protected $ratingServices;

	/**
	 * @var int
	 * @ORM\Column(type="integer")
	 */
	protected $ratingAttractions;

	/**
	 * @var int
	 * @ORM\Column(type="integer")
	 */
	protected $ratingPrice;

	/**
	 * @var float
	 * @ORM\Column(type="float")
	 */
	protected $avgRating;


	public function updateAvgRating()
	{
		$ratings = array_filter([
			$this->ratingLocation,
			$this->ratingCleanness,
			$this->ratingAmenities,
			$this->ratingPersonal,
			$this->ratingServices,
			$this->ratingAttractions,
			$this->ratingPrice
		]);
		$this->avgRating = round((array_sum($ratings) / count($ratings)),2);
	}


}
