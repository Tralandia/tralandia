<?php

namespace Entity\Emailing;

use Entity\Emailing;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="emailing_batch")
 */
class Batch extends \Entity\BaseEntityDetails {

	/**
	 * @var boolean
	 * @ORM\Column(type="boolean")
	 */
	protected $confirmed;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Email", inversedBy="batches")
	 */
	protected $emailTemplate;

	/**
	 * @var integer
	 * @ORM\Column(type="integer")
	 */
	protected $totalCount;

	/**
	 * @var text
	 * @ORM\Column(type="text")
	 */
	protected $subject;

	/**
	 * @var text
	 * @ORM\Column(type="text")
	 */
	protected $body;

	/**
	 * @var text
	 * @ORM\Column(type="text")
	 */
	protected $bodyHtml;

	




//@entity-generator-code <--- NEMAZAT !!!

	/* ----------------------------- Methods ----------------------------- */		
	public function __construct() {
		parent::__construct();
	}
		
	/**
	 * @param boolean
	 * @return \Entity\Emailing\Batch
	 */
	public function setConfirmed($confirmed) {
		$this->confirmed = $confirmed;

		return $this;
	}
		
	/**
	 * @return boolean|NULL
	 */
	public function getConfirmed() {
		return $this->confirmed;
	}
		
	/**
	 * @param \Entity\Emailing\Email
	 * @return \Entity\Emailing\Batch
	 */
	public function setEmailTemplate(\Entity\Emailing\Email $emailTemplate) {
		$this->emailTemplate = $emailTemplate;

		return $this;
	}
		
	/**
	 * @return \Entity\Emailing\Batch
	 */
	public function unsetEmailTemplate() {
		$this->emailTemplate = NULL;

		return $this;
	}
		
	/**
	 * @return \Entity\Emailing\Email|NULL
	 */
	public function getEmailTemplate() {
		return $this->emailTemplate;
	}
		
	/**
	 * @param integer
	 * @return \Entity\Emailing\Batch
	 */
	public function setTotalCount($totalCount) {
		$this->totalCount = $totalCount;

		return $this;
	}
		
	/**
	 * @return integer|NULL
	 */
	public function getTotalCount() {
		return $this->totalCount;
	}
		
	/**
	 * @param string
	 * @return \Entity\Emailing\Batch
	 */
	public function setSubject($subject) {
		$this->subject = $subject;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getSubject() {
		return $this->subject;
	}
		
	/**
	 * @param string
	 * @return \Entity\Emailing\Batch
	 */
	public function setBody($body) {
		$this->body = $body;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getBody() {
		return $this->body;
	}
		
	/**
	 * @param string
	 * @return \Entity\Emailing\Batch
	 */
	public function setBodyHtml($bodyHtml) {
		$this->bodyHtml = $bodyHtml;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getBodyHtml() {
		return $this->bodyHtml;
	}
}