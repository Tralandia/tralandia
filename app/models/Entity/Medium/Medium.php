<?php

namespace Entity\Medium;

use Entity\Dictionary;
use Entity\Attraction;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="medium_medium")
 */
class Medium extends \Entity\BaseEntityDetails {

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Type")
	 */
	protected $type;

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Entity\Dictionary\Phrase", cascade={"persist", "remove"})
	 */
	protected $name;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entity\Attraction\Attraction", inversedBy="media")
	 */
	protected $attraction;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entity\Rental\Rental", inversedBy="media")
	 */
	protected $rental;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entity\Seo\SeoUrl", inversedBy="media")
	 */
	protected $seoUrl;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entity\Ticket\Message", inversedBy="attachments")
	 */
	protected $message;

	/**
	 * @var text
	 * @ORM\Column(type="text")
	 */
	protected $uri;

	/**
	 * @var integer
	 * @ORM\Column(type="integer")
	 */
	protected $sort;


	





//@entity-generator-code <--- NEMAZAT !!!

	/* ----------------------------- Methods ----------------------------- */		
	public function __construct() {
		parent::__construct();
	}
		
	/**
	 * @param \Entity\Medium\Type
	 * @return \Entity\Medium\Medium
	 */
	public function setType(\Entity\Medium\Type $type) {
		$this->type = $type;

		return $this;
	}
		
	/**
	 * @return \Entity\Medium\Medium
	 */
	public function unsetType() {
		$this->type = NULL;

		return $this;
	}
		
	/**
	 * @return \Entity\Medium\Type|NULL
	 */
	public function getType() {
		return $this->type;
	}
		
	/**
	 * @param \Entity\Dictionary\Phrase
	 * @return \Entity\Medium\Medium
	 */
	public function setName(\Entity\Dictionary\Phrase $name) {
		$this->name = $name;

		return $this;
	}
		
	/**
	 * @return \Entity\Dictionary\Phrase|NULL
	 */
	public function getName() {
		return $this->name;
	}
		
	/**
	 * @param \Entity\Attraction\Attraction
	 * @return \Entity\Medium\Medium
	 */
	public function setAttraction(\Entity\Attraction\Attraction $attraction) {
		$this->attraction = $attraction;

		return $this;
	}
		
	/**
	 * @return \Entity\Medium\Medium
	 */
	public function unsetAttraction() {
		$this->attraction = NULL;

		return $this;
	}
		
	/**
	 * @return \Entity\Attraction\Attraction|NULL
	 */
	public function getAttraction() {
		return $this->attraction;
	}
		
	/**
	 * @param \Entity\Rental\Rental
	 * @return \Entity\Medium\Medium
	 */
	public function setRental(\Entity\Rental\Rental $rental) {
		$this->rental = $rental;

		return $this;
	}
		
	/**
	 * @return \Entity\Medium\Medium
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
	 * @param \Entity\Seo\SeoUrl
	 * @return \Entity\Medium\Medium
	 */
	public function setSeoUrl(\Entity\Seo\SeoUrl $seoUrl) {
		$this->seoUrl = $seoUrl;

		return $this;
	}
		
	/**
	 * @return \Entity\Medium\Medium
	 */
	public function unsetSeoUrl() {
		$this->seoUrl = NULL;

		return $this;
	}
		
	/**
	 * @return \Entity\Seo\SeoUrl|NULL
	 */
	public function getSeoUrl() {
		return $this->seoUrl;
	}
		
	/**
	 * @param \Entity\Ticket\Message
	 * @return \Entity\Medium\Medium
	 */
	public function setMessage(\Entity\Ticket\Message $message) {
		$this->message = $message;

		return $this;
	}
		
	/**
	 * @return \Entity\Medium\Medium
	 */
	public function unsetMessage() {
		$this->message = NULL;

		return $this;
	}
		
	/**
	 * @return \Entity\Ticket\Message|NULL
	 */
	public function getMessage() {
		return $this->message;
	}
		
	/**
	 * @param string
	 * @return \Entity\Medium\Medium
	 */
	public function setUri($uri) {
		$this->uri = $uri;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getUri() {
		return $this->uri;
	}
		
	/**
	 * @param integer
	 * @return \Entity\Medium\Medium
	 */
	public function setSort($sort) {
		$this->sort = $sort;

		return $this;
	}
		
	/**
	 * @return integer|NULL
	 */
	public function getSort() {
		return $this->sort;
	}
}