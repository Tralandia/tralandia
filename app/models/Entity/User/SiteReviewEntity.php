<?php

namespace Entity\User;

use Entity\Phrase;
use Entity\Rental;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="user_sitereview", indexes={@ORM\Index(name="senderEmail", columns={"senderEmail"})})
 */
class SiteReview extends \Entity\BaseEntity {

	const STATUS_PENDING = 0;
	const STATUS_APPROVED = 1;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entity\Language")
	 */
	protected $language;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entity\Location\Location")
	 */
	protected $primaryLocation;

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Entity\Rental\Rental", cascade={"persist", "remove"}, fetch="EAGER")
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
	 * @var text
	 * @ORM\Column(type="text")
	 */
	protected $testimonial;

	/**
	 * @var integer
	 * @ORM\Column(type="integer")
	 */
	protected $status = self::STATUS_PENDING;

	//@entity-generator-code --- NEMAZAT !!!

	/* ----------------------------- Methods ----------------------------- */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * @param \Entity\Language
	 * @return \Entity\User\SiteReview
	 */
	public function setLanguage(\Entity\Language $language)
	{
		$this->language = $language;

		return $this;
	}

	/**
	 * @return \Entity\User\SiteReview
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
	 * @param \Entity\Location\Location
	 * @return \Entity\User\SiteReview
	 */
	public function setPrimaryLocation(\Entity\Location\Location $primaryLocation)
	{
		$this->primaryLocation = $primaryLocation;

		return $this;
	}

	/**
	 * @return \Entity\User\SiteReview
	 */
	public function unsetPrimaryLocation()
	{
		$this->primaryLocation = NULL;

		return $this;
	}

	/**
	 * @return \Entity\Location\Location|NULL
	 */
	public function getPrimaryLocation()
	{
		return $this->primaryLocation;
	}

	/**
	 * @param \Entity\Rental\Rental
	 * @return \Entity\User\SiteReview
	 */
	public function setRental(\Entity\Rental\Rental $rental)
	{
		$this->rental = $rental;

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
	 * @return \Entity\User\SiteReview
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
	 * @return \Entity\User\SiteReview
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
	 * @param string
	 * @return \Entity\User\SiteReview
	 */
	public function setTestimonial($testimonial)
	{
		$this->testimonial = $testimonial;

		return $this;
	}

	/**
	 * @return string|NULL
	 */
	public function getTestimonial()
	{
		return $this->testimonial;
	}

	/**
	 * @param integer
	 * @return \Entity\User\SiteReview
	 */
	public function setStatus($status)
	{
		$this->status = $status;

		return $this;
	}

	/**
	 * @return integer|NULL
	 */
	public function getStatus()
	{
		return $this->status;
	}
}
