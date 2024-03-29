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

	const GROUP_TYPE_SOLO = 'a51';
	const GROUP_TYPE_YOUNG_PAIR = 'a52';
	const GROUP_TYPE_OLD_PAIR = 'a54';
	const GROUP_TYPE_GROUP = 'a53';
	const GROUP_TYPE_FRIENDS = 'a57';
	const GROUP_TYPE_FAMILY_YOUNG_KIDS = 'a55';
	const GROUP_TYPE_FAMILY_OLD_KIDS = 'a56';

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
	 * @ORM\Column(type="string", nullable=true)
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
	 * @ORM\Column(type="text", nullable=true)
	 */
	protected $messagePositives;

	/**
	 * @var string
	 * @ORM\Column(type="text", nullable=true)
	 */
	protected $messageNegatives;

	/**
	 * @var string
	 * @ORM\Column(type="text", nullable=true)
	 */
	protected $messageLocality;

	/**
	 * @var string
	 * @ORM\Column(type="text", nullable=true)
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


	/**
	 * @var string
	 * @ORM\Column(type="text", nullable=true)
	 */
	protected $ownerAnswer;



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

	public function getCustomerFullName()
	{
		return $this->senderFirstName . ' ' . $this->senderLastName;
	}

	public function hasAnswer()
	{
		return (boolean) strlen($this->ownerAnswer);
	}

}
