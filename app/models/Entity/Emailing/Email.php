<?php

namespace Entity\Emailing;

use Entity\Dictionary;
use Entity\Emailing;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="emailing_email")
 */
class Email extends \Entity\BaseEntity {

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $name;

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Entity\Dictionary\Phrase", cascade={"persist", "remove"})
	 */
	protected $subject;

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Entity\Dictionary\Phrase", cascade={"persist", "remove"})
	 */
	protected $body;

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Entity\Dictionary\Phrase", cascade={"persist", "remove"})
	 */
	protected $bodyHtml;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entity\Dictionary\Language")
	 */
	protected $language;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Type")
	 */
	protected $type;

	/**
	 * @var Collection
	 * @ORM\OneToMany(targetEntity="Batch", mappedBy="emailTemplate")
	 */
	protected $batches;

	














//@entity-generator-code <--- NEMAZAT !!!

	/* ----------------------------- Methods ----------------------------- */		
	public function __construct() {
		parent::__construct();

		$this->batches = new \Doctrine\Common\Collections\ArrayCollection;
	}
		
	/**
	 * @param string
	 * @return \Entity\Emailing\Email
	 */
	public function setName($name) {
		$this->name = $name;

		return $this;
	}
		
	/**
	 * @return \Entity\Emailing\Email
	 */
	public function unsetName() {
		$this->name = NULL;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getName() {
		return $this->name;
	}
		
	/**
	 * @param \Entity\Dictionary\Phrase
	 * @return \Entity\Emailing\Email
	 */
	public function setSubject(\Entity\Dictionary\Phrase $subject) {
		$this->subject = $subject;

		return $this;
	}
		
	/**
	 * @return \Entity\Dictionary\Phrase|NULL
	 */
	public function getSubject() {
		return $this->subject;
	}
		
	/**
	 * @param \Entity\Dictionary\Phrase
	 * @return \Entity\Emailing\Email
	 */
	public function setBody(\Entity\Dictionary\Phrase $body) {
		$this->body = $body;

		return $this;
	}
		
	/**
	 * @return \Entity\Dictionary\Phrase|NULL
	 */
	public function getBody() {
		return $this->body;
	}
		
	/**
	 * @param \Entity\Dictionary\Phrase
	 * @return \Entity\Emailing\Email
	 */
	public function setBodyHtml(\Entity\Dictionary\Phrase $bodyHtml) {
		$this->bodyHtml = $bodyHtml;

		return $this;
	}
		
	/**
	 * @return \Entity\Dictionary\Phrase|NULL
	 */
	public function getBodyHtml() {
		return $this->bodyHtml;
	}
		
	/**
	 * @param \Entity\Dictionary\Language
	 * @return \Entity\Emailing\Email
	 */
	public function setLanguage(\Entity\Dictionary\Language $language) {
		$this->language = $language;

		return $this;
	}
		
	/**
	 * @return \Entity\Emailing\Email
	 */
	public function unsetLanguage() {
		$this->language = NULL;

		return $this;
	}
		
	/**
	 * @return \Entity\Dictionary\Language|NULL
	 */
	public function getLanguage() {
		return $this->language;
	}
		
	/**
	 * @param \Entity\Emailing\Type
	 * @return \Entity\Emailing\Email
	 */
	public function setType(\Entity\Emailing\Type $type) {
		$this->type = $type;

		return $this;
	}
		
	/**
	 * @return \Entity\Emailing\Email
	 */
	public function unsetType() {
		$this->type = NULL;

		return $this;
	}
		
	/**
	 * @return \Entity\Emailing\Type|NULL
	 */
	public function getType() {
		return $this->type;
	}
		
	/**
	 * @param \Entity\Emailing\Batch
	 * @return \Entity\Emailing\Email
	 */
	public function addBatche(\Entity\Emailing\Batch $batche) {
		if(!$this->batches->contains($batche)) {
			$this->batches->add($batche);
		}
		$batche->setEmailTemplate($this);

		return $this;
	}
		
	/**
	 * @param \Entity\Emailing\Batch
	 * @return \Entity\Emailing\Email
	 */
	public function removeBatche(\Entity\Emailing\Batch $batche) {
		if($this->batches->contains($batche)) {
			$this->batches->removeElement($batche);
		}
		$batche->unsetEmailTemplate();

		return $this;
	}
		
	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection of \Entity\Emailing\Batch
	 */
	public function getBatches() {
		return $this->batches;
	}
}