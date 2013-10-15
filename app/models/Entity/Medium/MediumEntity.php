<?php

namespace Entity\Medium;

use Entity\Phrase;
use Doctrine\ORM\Mapping as ORM;
use	Extras\Annotation as EA;

/**
 * @ORM\Entity()
 * @ORM\Table(name="medium", indexes={@ORM\index(name="uri", columns={"uri"}), @ORM\index(name="oldUrl", columns={"oldUrl"}), @ORM\index(name="sort", columns={"sort"})})
 *
 */
class Medium extends \Entity\BaseEntityDetails {

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Type")
	 */
	protected $type;

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Entity\Phrase\Phrase", cascade={"persist", "remove"})
	 */
	protected $name;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entity\Seo\SeoUrl", inversedBy="media")
	 * @ORM\JoinColumn(onDelete="CASCADE")
	 */
	protected $seoUrl;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entity\Ticket\Message", inversedBy="attachments")
	 * @ORM\JoinColumn(onDelete="CASCADE")
	 */
	protected $message;

	/**
	 * @var text
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $uri;

	/**
	 * @var text
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $oldUrl;

	/**
	 * @var integer
	 * @ORM\Column(type="integer", nullable=true)
	 */
	protected $sort;

	//@entity-generator-code --- NEMAZAT !!!

	/* ----------------------------- Methods ----------------------------- */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * @param \Entity\Medium\Type
	 * @return \Entity\Medium\Medium
	 */
	public function setType(\Entity\Medium\Type $type)
	{
		$this->type = $type;

		return $this;
	}

	/**
	 * @return \Entity\Medium\Medium
	 */
	public function unsetType()
	{
		$this->type = NULL;

		return $this;
	}

	/**
	 * @return \Entity\Medium\Type|NULL
	 */
	public function getType()
	{
		return $this->type;
	}

	/**
	 * @param \Entity\Phrase\Phrase
	 * @return \Entity\Medium\Medium
	 */
	public function setName(\Entity\Phrase\Phrase $name)
	{
		$this->name = $name;

		return $this;
	}

	/**
	 * @return \Entity\Phrase\Phrase|NULL
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @param \Entity\Seo\SeoUrl
	 * @return \Entity\Medium\Medium
	 */
	public function setSeoUrl(\Entity\Seo\SeoUrl $seoUrl)
	{
		$this->seoUrl = $seoUrl;

		return $this;
	}

	/**
	 * @return \Entity\Medium\Medium
	 */
	public function unsetSeoUrl()
	{
		$this->seoUrl = NULL;

		return $this;
	}

	/**
	 * @return \Entity\Seo\SeoUrl|NULL
	 */
	public function getSeoUrl()
	{
		return $this->seoUrl;
	}

	/**
	 * @param \Entity\Ticket\Message
	 * @return \Entity\Medium\Medium
	 */
	public function setMessage(\Entity\Ticket\Message $message)
	{
		$this->message = $message;

		return $this;
	}

	/**
	 * @return \Entity\Medium\Medium
	 */
	public function unsetMessage()
	{
		$this->message = NULL;

		return $this;
	}

	/**
	 * @return \Entity\Ticket\Message|NULL
	 */
	public function getMessage()
	{
		return $this->message;
	}

	/**
	 * @param string
	 * @return \Entity\Medium\Medium
	 */
	public function setUri($uri)
	{
		$this->uri = $uri;

		return $this;
	}

	/**
	 * @return \Entity\Medium\Medium
	 */
	public function unsetUri()
	{
		$this->uri = NULL;

		return $this;
	}

	/**
	 * @return string|NULL
	 */
	public function getUri()
	{
		return $this->uri;
	}

	/**
	 * @param string
	 * @return \Entity\Medium\Medium
	 */
	public function setOldUrl($oldUrl)
	{
		$this->oldUrl = $oldUrl;

		return $this;
	}

	/**
	 * @return \Entity\Medium\Medium
	 */
	public function unsetOldUrl()
	{
		$this->oldUrl = NULL;

		return $this;
	}

	/**
	 * @return string|NULL
	 */
	public function getOldUrl()
	{
		return $this->oldUrl;
	}

	/**
	 * @param integer
	 * @return \Entity\Medium\Medium
	 */
	public function setSort($sort)
	{
		$this->sort = $sort;

		return $this;
	}

	/**
	 * @return \Entity\Medium\Medium
	 */
	public function unsetSort()
	{
		$this->sort = NULL;

		return $this;
	}

	/**
	 * @return integer|NULL
	 */
	public function getSort()
	{
		return $this->sort;
	}
}
