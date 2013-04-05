<?php

namespace Entity\Seo;

use Entity\Phrase;
use Entity\Location;
use Entity\Medium;
use Entity\Rental;
use Doctrine\ORM\Mapping as ORM;
use	Extras\Annotation as EA;

/**
 * @ORM\Entity()
 * @ORM\Table(name="seo_backlink", indexes={@ORM\index(name="rental", columns={"rental_id"}), @ORM\index(name="location", columns={"location_id"}), @ORM\index(name="language", columns={"language_id"})})
 * @EA\Primary(key="id", value="id")
 */
class BackLink extends \Entity\BaseEntity {

	const STATUS_OK = 'OK';
	const STATUS_NOT_FOUND = 'Not Found';
	const STATUS_PENDING = 'Pending';
	const STATUS_INCORRECT = 'Incorrect';

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entity\Rental\Rental")
	 * @ORM\JoinColumn(onDelete="SET NULL")
	 */
	protected $rental;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entity\Location\Location")
	 * @ORM\JoinColumn(onDelete="SET NULL")
	 */
	protected $location;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entity\Language")
	 * @ORM\JoinColumn(onDelete="SET NULL")
	 */
	protected $language;

	/**
	 * @var datetime
	 * @ORM\Column(type="datetime")
	 */
	protected $lastChecked;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $status;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $url;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $linkAnchor;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $linkTitle;

	/**
	 * @var text
	 * @ORM\Column(type="text")
	 */
	protected $htmlCode;

	/**
	 * @var text
	 * @ORM\Column(type="text")
	 */
	protected $notes;


			//@entity-generator-code --- NEMAZAT !!!

	/* ----------------------------- Methods ----------------------------- */		
	public function __construct()
	{
		parent::__construct();
	}
		
	/**
	 * @param \Entity\Rental\Rental
	 * @return \Entity\Seo\BackLink
	 */
	public function setRental(\Entity\Rental\Rental $rental)
	{
		$this->rental = $rental;

		return $this;
	}
		
	/**
	 * @return \Entity\Seo\BackLink
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
	 * @param \Entity\Location\Location
	 * @return \Entity\Seo\BackLink
	 */
	public function setLocation(\Entity\Location\Location $location)
	{
		$this->location = $location;

		return $this;
	}
		
	/**
	 * @return \Entity\Seo\BackLink
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
	 * @param \Entity\Language
	 * @return \Entity\Seo\BackLink
	 */
	public function setLanguage(\Entity\Language $language)
	{
		$this->language = $language;

		return $this;
	}
		
	/**
	 * @return \Entity\Seo\BackLink
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
	 * @param \DateTime
	 * @return \Entity\Seo\BackLink
	 */
	public function setLastChecked(\DateTime $lastChecked)
	{
		$this->lastChecked = $lastChecked;

		return $this;
	}
		
	/**
	 * @return \DateTime|NULL
	 */
	public function getLastChecked()
	{
		return $this->lastChecked;
	}
		
	/**
	 * @param string
	 * @return \Entity\Seo\BackLink
	 */
	public function setStatus($status)
	{
		$this->status = $status;

		return $this;
	}
		
	/**
	 * @return \Entity\Seo\BackLink
	 */
	public function unsetStatus()
	{
		$this->status = NULL;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getStatus()
	{
		return $this->status;
	}
		
	/**
	 * @param string
	 * @return \Entity\Seo\BackLink
	 */
	public function setUrl($url)
	{
		$this->url = $url;

		return $this;
	}
		
	/**
	 * @return \Entity\Seo\BackLink
	 */
	public function unsetUrl()
	{
		$this->url = NULL;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getUrl()
	{
		return $this->url;
	}
		
	/**
	 * @param string
	 * @return \Entity\Seo\BackLink
	 */
	public function setLinkAnchor($linkAnchor)
	{
		$this->linkAnchor = $linkAnchor;

		return $this;
	}
		
	/**
	 * @return \Entity\Seo\BackLink
	 */
	public function unsetLinkAnchor()
	{
		$this->linkAnchor = NULL;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getLinkAnchor()
	{
		return $this->linkAnchor;
	}
		
	/**
	 * @param string
	 * @return \Entity\Seo\BackLink
	 */
	public function setLinkTitle($linkTitle)
	{
		$this->linkTitle = $linkTitle;

		return $this;
	}
		
	/**
	 * @return \Entity\Seo\BackLink
	 */
	public function unsetLinkTitle()
	{
		$this->linkTitle = NULL;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getLinkTitle()
	{
		return $this->linkTitle;
	}
		
	/**
	 * @param string
	 * @return \Entity\Seo\BackLink
	 */
	public function setHtmlCode($htmlCode)
	{
		$this->htmlCode = $htmlCode;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getHtmlCode()
	{
		return $this->htmlCode;
	}
		
	/**
	 * @param string
	 * @return \Entity\Seo\BackLink
	 */
	public function setNotes($notes)
	{
		$this->notes = $notes;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getNotes()
	{
		return $this->notes;
	}
}