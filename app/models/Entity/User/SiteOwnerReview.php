<?php

namespace Entity\User;

use Entity\Phrase;
use Entity\Rental;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="user_siteownerreview", indexes={@ORM\index(name="senderEmail", columns={"senderEmail"})})
 */
class SiteOwnerReview extends \Entity\BaseEntity {

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
	 * @return \Entity\User\SiteOwnerReview
	 */
	public function setLanguage(\Entity\Language $language)
	{
		$this->language = $language;

		return $this;
	}
		
	/**
	 * @return \Entity\User\SiteOwnerReview
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
	 * @return \Entity\User\SiteOwnerReview
	 */
	public function setLocation(\Entity\Location\Location $location)
	{
		$this->location = $location;

		return $this;
	}
		
	/**
	 * @return \Entity\User\SiteOwnerReview
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
	 * @param \Extras\Types\Email
	 * @return \Entity\User\SiteOwnerReview
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
	 * @param string
	 * @return \Entity\User\SiteOwnerReview
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
	 * @return \Entity\User\SiteOwnerReview
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
	 * @return \Entity\User\SiteOwnerReview
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