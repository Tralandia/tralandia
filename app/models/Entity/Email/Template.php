<?php

namespace Entity\Email;

use Entity\Phrase;
use Doctrine\ORM\Mapping as ORM;
use	Extras\Annotation as EA;

/**
 * @ORM\Entity()
 * @ORM\Table(name="email_template")
 * @EA\Primary(key="id", value="domain")
 */
class Template extends \Entity\BaseEntity {

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entity\Email\TemplateType")
	 * @ORM\JoinColumn(onDelete="SET NULL")
	 */
	protected $type;

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
	 * @ORM\ManyToOne(targetEntity="Entity\Language")
	 */
	protected $language;

	/**
	 * @var Collection
	 * @ORM\OneToMany(targetEntity="Batch", mappedBy="template")
	 */
	protected $batches;

									//@entity-generator-code --- NEMAZAT !!!

	/* ----------------------------- Methods ----------------------------- */		
	public function __construct()
	{
		parent::__construct();

		$this->batches = new \Doctrine\Common\Collections\ArrayCollection;
	}
		
	/**
	 * @param \Entity\Email\TemplateType
	 * @return \Entity\Email\Template
	 */
	public function setType(\Entity\Email\TemplateType $type)
	{
		$this->type = $type;

		return $this;
	}
		
	/**
	 * @return \Entity\Email\Template
	 */
	public function unsetType()
	{
		$this->type = NULL;

		return $this;
	}
		
	/**
	 * @return \Entity\Email\TemplateType|NULL
	 */
	public function getType()
	{
		return $this->type;
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
	 * @param \Entity\Language
	 * @return \Entity\Email\Template
	 */
	public function setLanguage(\Entity\Language $language)
	{
		$this->language = $language;

		return $this;
	}
		
	/**
	 * @return \Entity\Email\Template
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
	 * @param \Entity\Email\Batch
	 * @return \Entity\Email\Template
	 */
	public function addBatche(\Entity\Email\Batch $batche)
	{
		if(!$this->batches->contains($batche)) {
			$this->batches->add($batche);
		}
		$batche->setTemplate($this);

		return $this;
	}
		
	/**
	 * @param \Entity\Email\Batch
	 * @return \Entity\Email\Template
	 */
	public function removeBatche(\Entity\Email\Batch $batche)
	{
		if($this->batches->contains($batche)) {
			$this->batches->removeElement($batche);
		}
		$batche->unsetTemplate();

		return $this;
	}
		
	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection of \Entity\Email\Batch
	 */
	public function getBatches()
	{
		return $this->batches;
	}
}