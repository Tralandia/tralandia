<?php

namespace Entity\User;

use Entity\Phrase;
use Entity\Rental;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="user_sitevisitorreview", indexes={@ORM\index(name="senderEmail", columns={"senderEmail"})})
 */
class SiteVisitorReview extends \Entity\BaseEntity {

	const STATUS_PENDING = 0;
	const STATUS_APROVED = 1;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entity\Language")
	 */
	protected $language;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entity\Location\Location")
	 */
	protected $location;

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
	 * @return \Entity\User\SiteVisitorReview
	 */
	public function setLanguage(\Entity\Language $language)
	{
		$this->language = $language;

		return $this;
	}
		
	/**
	 * @return \Entity\User\SiteVisitorReview
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
	 * @return \Entity\User\SiteVisitorReview
	 */
	public function setLocation(\Entity\Location\Location $location)
	{
		$this->location = $location;

		return $this;
	}
		
	/**
	 * @return \Entity\User\SiteVisitorReview
	 */
	public function unsetLocation()
	{
		$this->location = NULL;

		return $this;
	}
		
	/**
	 * @return \Entity\Location\Location|NULL
	 */
	public function getLocation()
	{
		return $this->location;
	}
		
	/**
	 * @param string
	 * @return \Entity\User\SiteVisitorReview
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
	 * @return \Entity\User\SiteVisitorReview
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
	 * @return \Entity\User\SiteVisitorReview
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
	 * @return \Entity\User\SiteVisitorReview
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