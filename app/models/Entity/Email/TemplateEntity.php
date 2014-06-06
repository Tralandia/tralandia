<?php

namespace Entity\Email;

use Entity\Phrase;
use Doctrine\ORM\Mapping as ORM;
use	Extras\Annotation as EA;

/**
 * @ORM\Entity()
 * @ORM\Table(name="email_template")
 *
 *
 */
class Template extends \Entity\BaseEntity {

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true, unique=true)
	 */
	protected $slug;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $name;

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Entity\Phrase\Phrase", cascade={"persist", "remove"})
	 */
	protected $subject;

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Entity\Phrase\Phrase", cascade={"persist", "remove"})
	 * this is in HTML format
	 */
	protected $body;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Layout")
	 */
	protected $layout;

	/**
	 * @var boolean
	 * @ORM\Column(type="boolean")
	 */
	protected $isNewsletter = false;


	/**
	 * @param string
	 * @return \Entity\Email\Template
	 */
	public function setSlug($slug)
	{
		$this->slug = \Nette\Utils\Strings::webalize($slug);

		return $this;
	}

	//@entity-generator-code --- NEMAZAT !!!

	/* ----------------------------- Methods ----------------------------- */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * @return \Entity\Email\Template
	 */
	public function unsetSlug()
	{
		$this->slug = NULL;

		return $this;
	}

	/**
	 * @return string|NULL
	 */
	public function getSlug()
	{
		return $this->slug;
	}

	/**
	 * @param string
	 * @return \Entity\Email\Template
	 */
	public function setName($name)
	{
		$this->name = $name;

		return $this;
	}

	/**
	 * @return \Entity\Email\Template
	 */
	public function unsetName()
	{
		$this->name = NULL;

		return $this;
	}

	/**
	 * @return string|NULL
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @param \Entity\Phrase\Phrase
	 * @return \Entity\Email\Template
	 */
	public function setSubject(\Entity\Phrase\Phrase $subject)
	{
		$this->subject = $subject;

		return $this;
	}

	/**
	 * @return \Entity\Phrase\Phrase|NULL
	 */
	public function getSubject()
	{
		return $this->subject;
	}

	/**
	 * @param \Entity\Phrase\Phrase
	 * @return \Entity\Email\Template
	 */
	public function setBody(\Entity\Phrase\Phrase $body)
	{
		$this->body = $body;

		return $this;
	}

	/**
	 * @return \Entity\Phrase\Phrase|NULL
	 */
	public function getBody()
	{
		return $this->body;
	}


	/**
	 * @param \Entity\Email\Layout
	 * @return \Entity\Email\Template
	 */
	public function setLayout(\Entity\Email\Layout $layout)
	{
		$this->layout = $layout;

		return $this;
	}

	/**
	 * @return \Entity\Email\Layout|NULL
	 */
	public function getLayout()
	{
		return $this->layout;
	}


}
