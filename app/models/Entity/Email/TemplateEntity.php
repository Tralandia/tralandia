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

	//@entity-generator-code --- NEMAZAT !!!

	/* ----------------------------- Methods ----------------------------- */		
	public function __construct()
	{
		parent::__construct();

		$this->batches = new \Doctrine\Common\Collections\ArrayCollection;
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
		$this->batches->removeElement($batche);
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